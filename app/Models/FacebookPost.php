<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $fillable = [
        'facebook_post_id',
        'facebook_page_id',
        'message',
        'story',
        'full_picture',
        'link',
        'post_url',
        'created_time',
        'imported_at',
        'news_id',
        'raw_data',
    ];

    protected $casts = [
        'created_time' => 'datetime',
        'imported_at' => 'datetime',
        'raw_data' => 'array',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
