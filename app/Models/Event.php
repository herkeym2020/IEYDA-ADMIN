<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'image',
        'event_date',
        'event_time',
        'location',
        'organizer',
        'phone',
        'attendees',
        'registration_fee',
        'registration_deadline',
        'contact_email',
        'registration_link',
        'highlights',
        'speakers',
        'outcomes',
        'status',
        'publish_status',
        'scheduled_at',
        'published_at',
        'is_featured',
    ];

    protected $casts = [
        'event_date' => 'date',
        'registration_deadline' => 'date',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'highlights' => 'array',
        'speakers' => 'array',
        'outcomes' => 'array',
    ];

    public function scopeUpcoming($query)
    {
        return $query->where('publish_status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('event_date', '>=', now())
            ->where('status', 'upcoming')
            ->orderBy('event_date');
    }

    public function scopePast($query)
    {
        return $query->where('publish_status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('status', 'completed')
            ->orderBy('event_date', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeScheduled($query)
    {
        return $query->where('publish_status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc');
    }
}
