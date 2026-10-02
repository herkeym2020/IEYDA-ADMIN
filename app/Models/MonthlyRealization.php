<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRealization extends Model
{
    use HasFactory;

    protected $fillable = [
        'month', 'title', 'community_name', 'lga', 'summary', 'details',
        'impact_metric', 'image', 'is_active', 'priority',
    ];

    protected $casts = [
        'month' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeLatestFeatured(Builder $query): Builder
    {
        return $query->visible()->orderByDesc('month')->orderByDesc('priority');
    }
}
