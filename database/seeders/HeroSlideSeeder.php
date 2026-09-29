<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlide::create([
            'title' => 'Empowering Youth',
            'subtitle' => 'Building Communities',
            'description' => 'The Ilorin Emirate Youth Development Association (IEYDA) is dedicated to fostering community development through youth empowerment and collaborative initiatives.',
            'badge' => 'Emirate Youth',
            'motto' => 'Love and Harmony',
            'yoruba_text' => 'Omo Ilorin Emirate ni mi…….Ilorin Emirate o ni baje faa',
            'primary_image' => 'hero-1.jpg',
            'overlay_image' => 'overlay-1.jpg',
            'ctas' => [
                ['text' => 'Explore Programs', 'link' => '/programs'],
                ['text' => 'Learn More', 'link' => '/about'],
            ],
            'order' => 1,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'title' => 'Community Development',
            'subtitle' => 'Through Unity',
            'description' => 'Join us in building stronger communities across the Ilorin Emirate through collaborative programs, youth empowerment, and sustainable development initiatives.',
            'badge' => 'Community Focus',
            'motto' => 'Unity in Diversity',
            'yoruba_text' => 'Agbajo owo la fi n so aya',
            'primary_image' => 'hero-2.jpg',
            'overlay_image' => 'overlay-2.jpg',
            'ctas' => [
                ['text' => 'Join Community', 'link' => '/community'],
                ['text' => 'View Programs', 'link' => '/programs'],
            ],
            'order' => 2,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'title' => 'Excellence in Leadership',
            'subtitle' => 'Inspiring Change',
            'description' => 'Developing the next generation of leaders through mentorship, training programs, and opportunities for growth across all sectors of society.',
            'badge' => 'Leadership',
            'motto' => 'Leading by Example',
            'yoruba_text' => 'Asiwaju ni a fi n se olori',
            'primary_image' => 'hero-3.jpg',
            'overlay_image' => 'overlay-3.jpg',
            'ctas' => [
                ['text' => 'Leadership Programs', 'link' => '/programs'],
                ['text' => 'Meet Our Team', 'link' => '/team'],
            ],
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
