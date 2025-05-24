<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photographer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class);
    }

    public function featuredImages()
    {
        return $this->hasMany(PortfolioImage::class)->where('is_featured', true);
    }

    public function views()
    {
        return $this->hasManyThrough(PortfolioView::class, PortfolioImage::class, 'portfolio_id', 'image_id');
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePublic($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('is_verified', true);
        });
    }

    // Accessors
    public function getTotalViewsAttribute()
    {
        return $this->views()->count();
    }

    public function getImageCountAttribute()
    {
        return $this->images()->count();
    }
}