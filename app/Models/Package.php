<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'photographer_id',
        'name',
        'category',
        'base_price',
        'duration_minutes',
        'includes',
        'excludes',
        'terms',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'includes' => 'array',
        'excludes' => 'array',
    ];

    // Relationships
    public function photographer()
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    public function addons()
    {
        return $this->hasMany(PackageAddon::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    public function scopeByDuration($query, $minutes)
    {
        return $query->where('duration_minutes', '<=', $minutes);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    public function getDurationHoursAttribute()
    {
        return round($this->duration_minutes / 60, 1);
    }

    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }
}
