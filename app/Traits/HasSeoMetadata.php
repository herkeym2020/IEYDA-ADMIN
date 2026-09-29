<?php

namespace App\Traits;

use App\Models\SeoMetadata;
use Illuminate\Support\Str;

trait HasSeoMetadata
{
    public static function bootHasSeoMetadata()
    {
        static::created(function ($model) {
            $model->createOrUpdateSeoMetadata();
        });

        static::updated(function ($model) {
            $model->createOrUpdateSeoMetadata();
        });

        static::deleted(function ($model) {
            $model->seoMetadata?->delete();
        });
    }

    public function seoMetadata()
    {
        return $this->hasOne(SeoMetadata::class, 'seoable_id')
            ->where('seoable_type', get_class($this));
    }

    public function createOrUpdateSeoMetadata()
    {
        $seo = $this->seoMetadata()->firstOrCreate([
            'seoable_type' => get_class($this),
            'seoable_id' => $this->id,
        ]);

        $seo->update([
            'meta_title' => $seo->meta_title ?? $this->title ?? '',
            'meta_description' => $seo->meta_description ?? (Str::limit($this->description ?? '', 160, '')) ?? '',
            'slug' => $seo->slug ?? Str::slug($this->title ?? 'item'),
            'word_count' => str_word_count(strip_tags($this->description ?? '')),
            'has_meta_title' => !empty($seo->meta_title),
            'has_meta_description' => !empty($seo->meta_description),
        ]);

        $seo->updateScore();

        return $seo;
    }

    public function getSeoTitle(): string
    {
        return $this->seoMetadata?->meta_title ?? $this->title ?? '';
    }

    public function getSeoDescription(): string
    {
        return $this->seoMetadata?->meta_description ?? Str::limit($this->description ?? '', 160, '');
    }

    public function getSeoSlug(): string
    {
        return $this->seoMetadata?->slug ?? Str::slug($this->title ?? 'item');
    }
}
