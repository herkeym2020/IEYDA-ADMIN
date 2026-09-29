<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Youth Skills Acquisition',
                'description' => 'Comprehensive training programs in various vocational skills including tailoring, carpentry, computer literacy, and entrepreneurship development.',
                'category' => 'Skills Training',
            ],
            [
                'title' => 'Educational Support',
                'description' => 'Scholarship programs, educational materials distribution, and academic mentorship for underprivileged youth in the Emirate.',
                'category' => 'Education',
            ],
            [
                'title' => 'Community Development',
                'description' => 'Infrastructure development projects, healthcare initiatives, and environmental conservation programs across local communities.',
                'category' => 'Community',
            ],
        ];

        foreach ($programs as $index => $program) {
            $slug = Str::slug($program['title']);
            Program::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $program['title'],
                    'description' => $program['description'],
                    'category' => $program['category'],
                    'image' => 'program-' . $slug . '.jpg',
                    'icon' => 'bi-award',
                    'beneficiaries' => '500+',
                    'budget' => '₦5,000,000',
                    'duration' => '6 months',
                    'start_date' => '2025-01-01',
                    'end_date' => '2025-06-30',
                    'objectives' => [
                        'Empower youth with practical skills',
                        'Promote entrepreneurship',
                    ],
                    'achievements' => [
                        'Trained 200+ youths',
                        'Established 10 new businesses',
                    ],
                    'partners' => [
                        'Local Government',
                        'NGO Partners',
                    ],
                    'locations' => [
                        'Ilorin',
                        'Offa',
                    ],
                    'progress' => 80,
                    'coordinator' => 'Amina Yakubu',
                    'order' => $index + 1,
                    'status' => 'active',
                    'is_active' => true,
                ]
            );
        }
    }
}
