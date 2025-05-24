<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'portfolio_id',
        'image_url',
        'thumbnail_url',
        'watermark',
        'exif_data',
        'tags',
        'is_featured',
        'uploaded_at',
    ];

    protected $casts = [
        'watermark' => 'boolean',
        'is_featured' => 'boolean',
        'uploaded_at' => 'datetime',
        'exif_data' => 'array',
        'tags' => 'array',
    ];

    // Relationships
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function views()
    {
        return $this->hasMany(PortfolioView::class, 'image_id');
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithWatermark($query)
    {
        return $query->where('watermark', true);
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    // Accessors
    public function getViewCountAttribute()
    {
        return $this->views()->count();
    }

    public function getTagListAttribute()
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }
}
