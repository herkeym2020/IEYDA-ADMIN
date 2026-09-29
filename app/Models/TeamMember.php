<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // grand_patron, board_of_trustees, executive_present, executive_pioneering, staff, volunteer
        'salute', // For grand patron
        'name',
        'awards', // For grand patron
        'position',
        'department',
        'image',
        'bio',
        'email',
        'phone',
        'location',
        'achievements',
        'social_links',
        'executive_type', // present, pioneering
        'term',
        'order',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'social_links' => 'array',
        'achievements' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
