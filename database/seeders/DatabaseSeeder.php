<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            HeroSlideSeeder::class,
            NewsSeeder::class,
            EventSeeder::class,
            ProgramSeeder::class,
            TeamMemberSeeder::class,
            GallerySeeder::class,
            TestimonialSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            CommunitySeeder::class,
            FeatureContentSeeder::class,
        ]);
    }
}
