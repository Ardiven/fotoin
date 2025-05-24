<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->with('roles')
            ->when($request->user_type, function ($query, $type) {
                return $query->where('user_type', $type);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                return $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:customer,photographer,admin',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'user_type' => $request->user_type,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        // Log admin action
        AdminLog::create([
            'admin_id' => auth()->id(),
            'action' => 'create_user',
            'description' => "Created user: {$user->name} ({$user->email})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'portfolios', 'packages', 'bookings']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:customer,photographer,admin',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'user_type', 'is_active']));
        $user->syncRoles([$request->role]);

        // Log admin action
        AdminLog::create([
            'admin_id' => auth()->id(),
            'action' => 'update_user',
            'description' => "Updated user: {$user->name} ({$user->email})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Prevent deleting super admin
        if ($user->hasRole('super-admin')) {
            return back()->withErrors(['error' => 'Cannot delete super admin']);
        }

        $userName = $user->name;
        $userEmail = $user->email;

        $user->delete();

        // Log admin action
        AdminLog::create([
            'admin_id' => auth()->id(),
            'action' => 'delete_user',
            'description' => "Deleted user: {$userName} ({$userEmail})",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        // Log admin action
        AdminLog::create([
            'admin_id' => auth()->id(),
            'action' => 'toggle_user_status',
            'description' => "User {$status}: {$user->name} ({$user->email})",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "User {$status} successfully");
    }
}