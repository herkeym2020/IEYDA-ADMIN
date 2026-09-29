<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuranCompetitionParticipant extends Model
{
    use HasFactory;

    protected $table = 'quran_competition_participants';

    protected $fillable = [
        'registration_number',
        'full_name',
        'gender',
        'date_of_birth',
        'age',
        'school',
        'lga',
        'state',
        'address',
        'guardian_name',
        'relationship',
        'phone',
        'email',
        'emergency_name',
        'emergency_phone',
        'madrasah',
        'teacher_name',
        'teacher_phone',
        'category',
        'photo_path',
        'decl_age',
        'decl_accurate',
        'decl_consent',
        'decl_rules',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'category' => 'array',
        'decl_age' => 'boolean',
        'decl_accurate' => 'boolean',
        'decl_consent' => 'boolean',
        'decl_rules' => 'boolean',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeFinalists($query)
    {
        return $query->where('status', 'finalist');
    }

    public function scopeWinners($query)
    {
        return $query->where('status', 'winner');
    }

    // Helper to generate unique registration number
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $prefix = "IEYDA-QRC-{$year}-";
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