<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentDraft extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'content_type',
        'content_id',
        'title',
        'description',
        'content',
        'image',
        'metadata',
        'scheduled_at',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at > now();
    }

    public function isAutoSaved(): bool
    {
        return $this->status === 'auto-saved';
    }

    public function isReadyToPublish(): bool
    {
        return $this->scheduled_at && $this->scheduled_at <= now();
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now());
    }

    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }
}
