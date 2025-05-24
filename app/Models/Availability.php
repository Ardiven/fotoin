<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Availability extends Model
{
    use HasFactory;

    const STATUS_AVAILABLE = 'available';
    const STATUS_BUSY = 'busy';
    const STATUS_BLOCKED = 'blocked';

    protected $fillable = [
        'photographer_id',
        'status',
        'start_time',
        'end_time',
        'note',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationships
    public function photographer()
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeBusy($query)
    {
        return $query->where('status', self::STATUS_BUSY);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('start_time', '<=', $date)
                    ->whereDate('end_time', '>=', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    // Methods
    public static function isPhotographerAvailable($photographerId, $startTime, $endTime)
    {
        return !static::where('photographer_id', $photographerId)
                     ->where('status', '!=', self::STATUS_AVAILABLE)
                     ->where(function ($query) use ($startTime, $endTime) {
                         $query->whereBetween('start_time', [$startTime, $endTime])
                               ->orWhereBetween('end_time', [$startTime, $endTime])
                               ->orWhere(function ($q) use ($startTime, $endTime) {
                                   $q->where('start_time', '<=', $startTime)
                                     ->where('end_time', '>=', $endTime);
                               });
                     })
                     ->exists();
    }

    // Accessors
    public function getDurationAttribute()
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_BUSY => 'warning',
            self::STATUS_BLOCKED => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}
