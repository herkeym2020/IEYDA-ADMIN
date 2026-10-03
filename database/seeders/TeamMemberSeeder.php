<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            // Grand Patron
            [
                'type' => 'grand_patron',
                'salute' => 'His Royal Highness',
                'name' => 'Alh. (Dr.) Ibrahim Sulu-Gambari',
                'awards' => 'CFR, OON',
                'position' => 'Emir of Ilorin',
                'bio' => 'Chairman, Kwara State Traditional Council of Chiefs and Obas, and Chancellor, Bayero University Kano (BUK).',
                'image' => 'team-member-1.jpg',
                'location' => 'Ilorin, Kwara State',
                'achievements' => ['Traditional Leadership Excellence', 'Educational Development', 'Community Service'],
                'order' => 1,
            ],
            // Board of Trustees
            [
                'type' => 'board_of_trustees',
                'name' => 'Alh. Abdullahi Bello',
                'position' => 'Chairman, Board of Trustees',
                'bio' => 'Long-standing supporter and advisor to the association.',
                'image' => 'team-member-2.jpg',
                'location' => 'Ilorin, Kwara State',
                'achievements' => ['Board Leadership', 'Strategic Planning', 'Community Development'],
                'order' => 2,
            ],
            // Executive Present
            [
                'type' => 'executive_present',
                'name' => 'Alh. Mohammed Uthman Jagunmo',
                'position' => 'National President',
                'executive_type' => 'present',
                'term' => '2024-2026',
                'department' => 'Executive',
                'priority' => 1,
                'bio' => 'A visionary leader committed to youth empowerment and community development across the Ilorin Emirate.',
                'image' => 'team-member-3.jpg',
                'location' => 'Ilorin, Kwara State',
                'achievements' => ['Youth Empowerment Programs', 'Community Development Initiatives', 'Leadership Excellence'],
                'order' => 3,
            ],
            // Executive Pioneering
            [
                'type' => 'executive_pioneering',
                'name' => 'Alh. Ibrahim Suleiman',
                'position' => 'Vice President',
                'executive_type' => 'pioneering',
                'term' => '2010-2012',
                'department' => 'Executive',
                'priority' => 2,
                'bio' => 'Dedicated to supporting youth initiatives and sustainable community programs.',
                'image' => 'team-member-4.jpg',
                'location' => 'Ilorin, Kwara State',
                'achievements' => ['Program Development', 'Youth Mentorship', 'Community Outreach'],
                'order' => 4,
            ],
            // Staff
            [
                'type' => 'staff',
                'name' => 'Mrs. Fatima Abdullahi',
                'position' => 'General Secretary',
                'department' => 'Admin',
                'bio' => 'Coordinating all IEYDA activities with passion for youth empowerment.',
                'image' => 'team-member-5.jpg',
                'location' => 'Ilorin, Kwara State',
                'achievements' => ['Administrative Excellence', 'Event Coordination', 'Team Management'],
                'order' => 5,
            ],
        ];

        foreach ($members as $member) {
            $attributes = array_merge([
                'email' => strtolower(str_replace(' ', '.', $member['name'])) . '@ieyda.org',
                'phone' => '+234 803 xxx xxxx',
                'social_links' => [
                    ['platform' => 'facebook', 'url' => '#'],
                    ['platform' => 'twitter', 'url' => '#'],
                    ['platform' => 'linkedin', 'url' => '#'],
                ],
                'is_active' => true,
            ], $member);

            TeamMember::updateOrCreate(
                ['type' => $member['type'], 'name' => $member['name']],
                $attributes
            );
        }
    }
}
