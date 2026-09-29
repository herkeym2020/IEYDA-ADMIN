<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialMemberLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source_form_id',
        'form_submitted_at',
        'data',
        'status',
        'tag',
        'notes',
        'interaction_count',
        'ack_sent_at',
        'contacted_at',
        'last_interaction_at',
    ];

    protected $casts = [
        'data' => 'array',
        'form_submitted_at' => 'datetime',
        'ack_sent_at' => 'datetime',
        'contacted_at' => 'datetime',
        'last_interaction_at' => 'datetime',
    ];

    // Relationships
    public function activities(): HasMany
    {
        return $this->hasMany(FinancialLeadActivity::class, 'lead_id');
    }

    // Helper methods
    public function logActivity(string $type, ?string $description = null, ?array $data = null): void
    {
        $this->activities()->create([
            'type' => $type,
            'description' => $description,
            'data' => $data,
        ]);

        $this->update([
            'interaction_count' => $this->interaction_count + 1,
            'last_interaction_at' => now(),
        ]);
    }

    public function addNote(string $note): void
    {
        $this->update(['notes' => $note]);
        $this->logActivity('note_added', $note);
    }

    public function setTag(string $tag): void
    {
        $this->update(['tag' => $tag]);
        $this->logActivity('tag_added', "Tagged as {$tag}");
    }

    public function markContacted(): void
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
        ]);
        $this->logActivity('contacted', 'Lead marked as contacted');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new' => 'warning',
            'contacted' => 'info',
            'qualified' => 'success',
            'converted' => 'primary',
            default => 'secondary',
        };
    }

    public function getTagColorAttribute(): string
    {
        return match ($this->tag) {
            'bronze' => 'secondary',
            'silver' => 'info',
            'gold' => 'warning',
            'premium' => 'danger',
            default => 'light',
        };
    }
}

