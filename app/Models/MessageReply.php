<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_message_id',
        'reply_text',
        'reply_from',
        'reply_template',
        'sent_to_user',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with ContactMessage
     */
    public function message()
    {
        return $this->belongsTo(ContactMessage::class, 'contact_message_id');
    }
}
