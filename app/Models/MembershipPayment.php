<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPayment extends Model
{
    protected $fillable = [
        'reference',
        'email',
        'name',
        'phone',
        'amount',
        'status',
        'form_access_token',
        'paid_at',
        'transaction_data',
    ];

    protected $casts = [
        'transaction_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->hasOne(FinancialMemberLead::class, 'email', 'email');
    }
}
