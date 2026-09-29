<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'image',
        'icon',
        'beneficiaries',
        'budget',
        'duration',
        'start_date',
        'end_date',
        'objectives',
        'achievements',
        'partners',
        'locations',
        'progress',
        'coordinator',
        'order',
        'status',
        'publish_status',
        'scheduled_at',
        'published_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'objectives' => 'array',
        'achievements' => 'array',
        'partners' => 'array',
        'locations' => 'array',
        'progress' => 'integer',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('publish_status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('order');
    }

    public function scopeScheduled($query)
    {
        return $query->where('publish_status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc');
    }
}
