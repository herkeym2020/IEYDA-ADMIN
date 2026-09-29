<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'IEYDA', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Ilorin Emirate Youth Development Association', 'group' => 'general'],

            // Identity (files stored under storage/app/public/settings)
            ['key' => 'logo', 'value' => null, 'group' => 'identity'],
            ['key' => 'favicon', 'value' => null, 'group' => 'identity'],
            ['key' => 'og_image', 'value' => null, 'group' => 'identity'],

            // SEO
            ['key' => 'seo_title', 'value' => 'IEYDA - Empowering Youth in Ilorin Emirate', 'group' => 'seo'],
            ['key' => 'seo_description', 'value' => 'Official website of Ilorin Emirate Youth Development Association (IEYDA). Stay updated on programs, events, and community impact.', 'group' => 'seo'],
            ['key' => 'seo_keywords', 'value' => 'IEYDA, Ilorin, Emirate, Youth, Development, Programs, Events', 'group' => 'seo'],

            // Contact
            ['key' => 'contact_email', 'value' => 'info@ieyda.org', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+234 803 123 4567', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Aishat Adepate House, Edun Street, Ilorin, Kwara State, Nigeria', 'group' => 'contact'],

            // Social
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/ieyda', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/ieyda', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://www.instagram.com/ieyda', 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => 'https://www.linkedin.com/company/ieyda', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
