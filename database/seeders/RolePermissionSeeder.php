<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Portfolio permissions
            'view portfolios',
            'create portfolios',
            'edit portfolios',
            'delete portfolios',
            'manage own portfolio',
            
            // Package permissions
            'view packages',
            'create packages',
            'edit packages',
            'delete packages',
            'manage own packages',
            
            // Booking permissions
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            'manage own bookings',
            
            // Transaction permissions
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
            
            // Chat permissions
            'view chats',
            'send messages',
            'delete messages',
            
            // Availability permissions
            'view availability',
            'manage availability',
            
            // Admin permissions
            'view admin logs',
            'manage system',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin Role
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin Role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view portfolios',
            'view packages',
            'view bookings',
            'view transactions',
            'view chats',
            'view availability',
            'view admin logs',
        ]);

        // Photographer Role
        $photographerRole = Role::create(['name' => 'photographer']);
        $photographerRole->givePermissionTo([
            'view portfolios',
            'manage own portfolio',
            'view packages',
            'manage own packages',
            'view bookings',
            'manage own bookings',
            'view transactions',
            'view chats',
            'send messages',
            'view availability',
            'manage availability',
        ]);

        // Customer Role
        $customerRole = Role::create(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view portfolios',
            'view packages',
            'create bookings',
            'manage own bookings',
            'view chats',
            'send messages',
        ]);
    }
}

