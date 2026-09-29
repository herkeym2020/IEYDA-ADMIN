<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'category', 'ip_address', 'user_agent', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with MessageReplies
     */
    public function replies()
    {
        return $this->hasMany(MessageReply::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest reply
     */
    public function latestReply()
    {
        return $this->hasOne(MessageReply::class)->latest();
    }
}
