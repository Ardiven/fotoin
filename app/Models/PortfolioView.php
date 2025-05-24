<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioView extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_id',
        'viewer_ip',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    // Relationships
    public function image()
    {
        return $this->belongsTo(PortfolioImage::class, 'image_id');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('viewed_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('viewed_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('viewed_at', now()->month)
                    ->whereYear('viewed_at', now()->year);
    }

    // Static methods
    public static function recordView($imageId, $ip = null)
    {
        return static::create([
            'image_id' => $imageId,
            'viewer_ip' => $ip ?: request()->ip(),
            'viewed_at' => now(),
        ]);
    }
}