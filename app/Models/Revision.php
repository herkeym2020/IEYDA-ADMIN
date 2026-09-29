<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revision extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'revisionable_type',
        'revisionable_id',
        'key',
        'old_value',
        'new_value',
        'action',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRevisionableModel()
    {
        return app($this->revisionable_type)
            ->find($this->revisionable_id);
    }

    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('revisionable_type', $type)
            ->where('revisionable_id', $id);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->latest()->limit($limit);
    }
}
