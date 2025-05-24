<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'photographer_id');
    }

    public function customerBookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function photographerBookings()
    {
        return $this->hasMany(Booking::class, 'photographer_id');
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'photographer_id');
    }

    public function adminLogs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }

    // Helper methods
    public function isPhotographer()
    {
        return $this->hasRole('photographer');
    }

    public function isCustomer()
    {
        return $this->hasRole('customer');
    }

    public function isAdmin()
    {
        return $this->hasRole(['admin', 'super-admin']);
    }
}