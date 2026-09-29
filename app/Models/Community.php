<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lga',
        'description',
        'status', // 'approved', 'pending', 'declined'
        'contact_name',
        'contact_email',
        'contact_phone',
        'position',
        'address',
    ];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }
}
