<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IctProgrammeApplicant extends Model
{
    use HasFactory;

    protected $table = 'ict_programme_applicants';

    protected $fillable = [
        'registration_number', 'full_name', 'gender', 'date_of_birth', 'age',
        'phone', 'email', 'address', 'lga', 'state', 'education_level', 'occupation',
        'ict_courses', 'vocational_interests', 'expectations', 'photo_path',
        'guardian_name', 'guardian_phone', 'guardian_relationship',
        'decl_accurate', 'decl_consent', 'decl_rules',
        'status', 'admin_notes', 'ip_address', 'user_agent',
        'admitted_at', 'email_sent_at', 'whatsapp_sent_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'ict_courses' => 'array',
        'vocational_interests' => 'array',
        'decl_accurate' => 'boolean',
        'decl_consent' => 'boolean',
        'decl_rules' => 'boolean',
        'admitted_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
    ];

    // Query scopes for each status
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeApproved($query) { return $query->where('status', 'approved'); }
    public function scopeAdmitted($query) { return $query->where('status', 'admitted'); }
    public function scopeRejected($query) { return $query->where('status', 'rejected'); }
    public function scopeCompleted($query) { return $query->where('status', 'completed'); }
    public function scopeGraduated($query) { return $query->where('status', 'graduated'); }

    /**
     * Generate a unique registration number
     */
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $prefix = 'IEYDA-ICT';

        $latest = self::where('registration_number', 'like', "{$prefix}-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $parts = explode('-', $latest->registration_number);
            $num = (int)end($parts);
            $num++;
        } else {
            $num = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $num);
    }

    /**
     * Get the ICT courses list
     */
    public static function getIctCourses(): array
    {
        return [
            'digital_marketing' => 'Digital Marketing',
            'document_management' => 'Document Management',
            'digital_presentation' => 'Digital Presentation',
            'graphics_design' => 'Graphics Design',
            'virtual_classroom' => 'Virtual Classroom',
            'general_ai' => 'General Artificial Intelligence (AI)',
            'cctv_installation' => 'CCTV Installation and Maintenance',
        ];
    }

    /**
     * Get the vocational sessions list
     */
    public static function getVocationalSessions(): array
    {
        return [
            'custard_production' => 'Custard and Powdered Milk Production',
            'air_freshener' => 'Air Freshener Production',
            'perfume_balm' => 'Perfume and Balm Production',
            'liquid_soap' => 'Liquid Soap Making',
            'scouring_powder' => 'Scouring Powder Making',
        ];
    }

    /**
     * Get formatted ICT courses
     */
    public function getFormattedIctCoursesAttribute(): string
    {
        $courses = $this->ict_courses ?? [];
        $allCourses = self::getIctCourses();
        $labels = [];
        foreach ($courses as $course) {
            $labels[] = $allCourses[$course] ?? ucfirst(str_replace('_', ' ', $course));
        }
        return implode(', ', $labels);
    }

    /**
     * Get formatted vocational interests
     */
    public function getFormattedVocationalInterestsAttribute(): string
    {
        $interests = $this->vocational_interests ?? [];
        $allInterests = self::getVocationalSessions();
        $labels = [];
        foreach ($interests as $interest) {
            $labels[] = $allInterests[$interest] ?? ucfirst(str_replace('_', ' ', $interest));
        }
        return implode(', ', $labels);
    }

    /**
     * Get status color for badges
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'info',
            'admitted' => 'success',
            'rejected' => 'danger',
            'completed' => 'primary',
            'graduated' => 'purple',
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}