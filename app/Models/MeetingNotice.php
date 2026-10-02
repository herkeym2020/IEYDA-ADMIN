<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingNotice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'meeting_type', 'summary', 'details', 'starts_at', 'ends_at',
        'location', 'action_label', 'action_url', 'is_active', 'show_popup', 'priority',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'show_popup' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('starts_at', '>=', now()->subDay());
    }

    public function scopePopup(Builder $query): Builder
    {
        return $query->visible()->where('show_popup', true);
    }
}
