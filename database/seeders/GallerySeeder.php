<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Gallery::create([
                'title' => 'Gallery Image ' . $i,
                'description' => 'Description for gallery image ' . $i,
                'image' => 'https://picsum.photos/400/300?random=' . ($i + 1000),
                'category' => ['events', 'programs', 'community'][rand(0, 2)],
                'event_date' => now()->subDays(rand(1, 365)),
                'order' => $i,
                'is_active' => true,
            ]);
        }
    }
}
