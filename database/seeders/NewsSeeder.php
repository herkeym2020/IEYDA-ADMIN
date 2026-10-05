<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            [
                'title' => 'IEYDA Launches New Skills Acquisition Center',
                'excerpt' => 'A state-of-the-art facility equipped with modern tools and equipment for vocational training has been officially opened in Ilorin.',
                'category' => 'Infrastructure',
            ],
            [
                'title' => '100 Youth Benefit from Scholarship Program',
                'excerpt' => 'The annual scholarship program has awarded educational grants to deserving students across the Ilorin Emirate.',
                'category' => 'Education',
            ],
            [
                'title' => 'Community Health Outreach Reaches 500 Families',
                'excerpt' => 'Free medical checkups and health education programs conducted in rural communities with overwhelming response.',
                'category' => 'Health',
            ],
        ];

        foreach ($news as $item) {
            $slug = Str::slug($item['title']);
            News::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => '<p>' . $item['excerpt'] . '</p><p>Full article content goes here with more details...</p>',
                    'category' => $item['category'],
                    'image' => 'https://picsum.photos/600/400?random=' . rand(1, 1000),
                    'author' => 'IEYDA Admin',
                    'read_time' => '3 min read',
                    'published_at' => now()->subDays(rand(1, 30)),
                    'publish_status' => 'published',
                    'is_featured' => rand(0, 1),
                    'is_published' => true,
                ]
            );
        }
    }
}
