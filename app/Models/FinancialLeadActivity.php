<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialLeadActivity extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'lead_id',
        'type',
        'description',
        'data',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(FinancialMemberLead::class, 'lead_id');
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'email_sent' => '✉️',
            'email_opened' => '📬',
            'note_added' => '📝',
            'status_changed' => '🔄',
            'tag_added' => '🏷️',
            'contacted' => '📞',
            default => '📋',
        };
    }
}
