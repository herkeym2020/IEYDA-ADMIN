<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMetadata extends Model
{
    use HasFactory;

    protected $table = 'seo_metadata';

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'meta_title',
        'meta_description',
        'slug',
        'og_image',
        'og_description',
        'canonical_url',
        'schema_markup',
        'keywords',
        'keyword_density',
        'seo_score',
        'word_count',
        'has_meta_title',
        'has_meta_description',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'has_meta_title' => 'boolean',
        'has_meta_description' => 'boolean',
    ];

    public function calculateScore(): string
    {
        $score = 0;

        if ($this->has_meta_title) $score += 30;
        if ($this->has_meta_description) $score += 25;
        if ($this->keyword_density > 0) $score += 20;
        if ($this->og_image) $score += 15;
        if ($this->word_count >= 300) $score += 10;

        if ($score >= 80) {
            return 'excellent';
        } elseif ($score >= 50) {
            return 'good';
        } else {
            return 'poor';
        }
    }

    public function updateScore(): void
    {
        $this->seo_score = $this->calculateScore();
        $this->save();
    }

    public function getScoreAttribute(): string
    {
        return $this->calculateScore();
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('seoable_type', $type)
            ->where('seoable_id', $id);
    }

    public function scopeExcellentScore($query)
    {
        return $query->where('seo_score', 'excellent');
    }
}
