<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Fatima Abdullahi',
                'role' => 'Skills Acquisition Graduate',
                'content' => "IEYDA's tailoring program changed my life completely. I now run my own fashion business and employ three other young women.",
                'program' => 'Tailoring & Fashion Design',
            ],
            [
                'name' => 'Ibrahim Suleiman',
                'role' => 'Scholarship Recipient',
                'content' => "Thanks to IEYDA's scholarship program, I was able to complete my university education and now work as a software engineer.",
                'program' => 'Educational Support',
            ],
            [
                'name' => 'Aisha Mohammed',
                'role' => 'Leadership Program Graduate',
                'content' => 'The leadership training gave me confidence and skills to start a youth organization in my community.',
                'program' => 'Leadership Development',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create([
                'name' => $testimonial['name'],
                'role' => $testimonial['role'],
                'content' => $testimonial['content'],
                'image' => 'testimonial-' . strtolower(str_replace(' ', '-', $testimonial['name'])) . '.jpg',
                'program' => $testimonial['program'],
                'year' => '2024',
                'rating' => 5,
                'is_featured' => rand(0, 1),
                'is_active' => true,
            ]);
        }
    }
}
