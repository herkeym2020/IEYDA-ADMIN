<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('pages')->updateOrInsert(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'content' => 'Edit this about page content.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        DB::table('pages')->updateOrInsert(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'content' => 'Edit this contact page content.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
