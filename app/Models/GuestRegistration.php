<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuestRegistration extends Model
{
    use HasFactory;

    protected $table = 'guest_registrations';

    protected $fillable = [
        'registration_number',
        'full_name',
        'email',
        'phone',
        'organization',
        'guest_type',
        'status',
        'pdf_path',
        'ip_address',
        'user_agent',
        'device_hash',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Helper to generate unique registration number
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $prefix = "IEYDA-GST-{$year}-";
        $latest = self::where('registration_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $next = 1;
        if ($latest) {
            $parts = explode('-', $latest->registration_number);
            $next = (int) end($parts) + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}