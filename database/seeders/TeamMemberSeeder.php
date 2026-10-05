<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
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
                'priority' => 1,
            ],
            [
                'type' => 'board_of_trustees',
                'name' => 'Prof. Abdulganiy Ambali',
                'position' => 'Chairman, Board of Trustees',
                'bio' => 'Chairman of the IEYDA Board of Trustees.',
                'location' => 'Ilorin, Kwara State',
                'order' => 2,
                'priority' => 1,
            ],
            [
                'type' => 'board_of_trustees',
                'name' => 'Hajia Habibat Saliman Saidu',
                'position' => 'Secretary, Board of Trustees',
                'location' => 'Ilorin, Kwara State',
                'order' => 3,
                'priority' => 2,
            ],
        ];

        $pioneering = [
            ['name' => 'Alh. Abdullahi Babatunde Salau', 'position' => 'National President', 'lga' => 'Ilorin East', 'priority' => 1, 'order' => 10],
            ['name' => 'Alh. Muritala Soliu Ottan', 'position' => 'National Vice President', 'lga' => 'Ilorin West', 'priority' => 2, 'order' => 11],
            ['name' => 'Barr. Tajudeen Akanbi Abdullahi', 'position' => 'National Secretary', 'lga' => 'Ilorin East', 'priority' => 3, 'order' => 12],
            ['name' => 'Engr. Ahmed Kazeem Jatto', 'position' => 'National Admin. Secretary', 'lga' => 'Ilorin West', 'priority' => 4, 'order' => 13],
            ['name' => 'Mall. Abdulmumini Abubakar', 'position' => 'National Asst. Secretary', 'lga' => 'Ilorin South', 'priority' => 5, 'order' => 14],
            ['name' => 'Mrs. Muinat Alawaye', 'position' => 'National Financial Secretary', 'lga' => 'Ilorin West', 'priority' => 6, 'order' => 15],
            ['name' => 'Mr. Kadir Rasheed Ajao', 'position' => 'National Asst. Financial Secretary', 'lga' => 'Ilorin East', 'priority' => 7, 'order' => 16],
            ['name' => 'Mrs. Gobir Hawau Kemi', 'position' => 'National Treasurer', 'lga' => 'Ilorin East', 'priority' => 8, 'order' => 17],
            ['name' => 'Dr. Yahya Wasiu O.', 'position' => 'National Auditor', 'lga' => 'Ilorin West', 'priority' => 9, 'order' => 18],
            ['name' => 'Mr. Salman Abdulrasak Olaitan', 'position' => 'National Deputy Auditor', 'lga' => 'Ilorin West', 'priority' => 10, 'order' => 19],
            ['name' => 'Alh. Mohammed Uthman Jagunmo', 'position' => 'National Publicity Secretary', 'lga' => 'Ilorin South', 'priority' => 11, 'order' => 20],
            ['name' => 'Mr. Akano Babatunde', 'position' => 'National Asst. Publicity Secretary', 'lga' => 'Moro', 'priority' => 12, 'order' => 21],
            ['name' => 'Mrs. Mohammed Khafilat', 'position' => 'National Social Director', 'lga' => 'Asa', 'priority' => 13, 'order' => 22],
            ['name' => 'Mrs. Uthman T. Rasheedat Oke', 'position' => 'National Asst. Social Director', 'lga' => 'Ilorin West', 'priority' => 14, 'order' => 23],
            ['name' => 'Mall. Yusuf Teslim', 'position' => 'National Welfare Director', 'lga' => 'Ilorin South', 'priority' => 15, 'order' => 24],
            ['name' => 'Mr. Ajape Kabir', 'position' => 'National Asst. Welfare Director', 'lga' => 'Ilorin East', 'priority' => 16, 'order' => 25],
            ['name' => 'Alh. Abdullahi Abdulfatai Oloyin', 'position' => 'Director of ICT Centre', 'lga' => 'Ilorin West', 'priority' => 17, 'order' => 26],
        ];

        $present = [
            ['name' => 'Alh. Mohammed Uthman Jagunmo', 'position' => 'National President', 'lga' => 'Ilorin South', 'priority' => 1, 'order' => 30],
            ['name' => 'Alh. Muritala Soliu Ottan', 'position' => 'National Vice President (Admin)', 'lga' => 'Ilorin West', 'priority' => 2, 'order' => 31],
            ['name' => 'Mr. Ishola Saka', 'position' => 'National Vice President (Special Duty)', 'lga' => 'Asa', 'priority' => 3, 'order' => 32],
            ['name' => 'Engr. Ahmed Kazeem Jatto, FNIMechE', 'position' => 'National Secretary', 'lga' => 'Ilorin West', 'priority' => 4, 'order' => 33],
            ['name' => 'Dr. Yahaya Quadir', 'position' => 'National Asst. Secretary I', 'lga' => 'Ilorin South', 'priority' => 5, 'order' => 34],
            ['name' => 'Mr. Abdullahi Adio', 'position' => 'National Asst. Secretary II', 'lga' => 'Moro', 'priority' => 6, 'order' => 35],
            ['name' => 'Mr. Kadir Rasheed Ajao', 'position' => 'National Financial Secretary', 'lga' => 'Ilorin East', 'priority' => 7, 'order' => 36],
            ['name' => 'Mrs. Aminat Abdulganiyu', 'position' => 'National Asst. Financial Secretary', 'lga' => 'Ilorin West', 'priority' => 8, 'order' => 37],
            ['name' => 'Mr. Yusuf Isiaka Baba-Ago', 'position' => 'National Treasurer', 'lga' => 'Moro', 'priority' => 9, 'order' => 38],
            ['name' => 'Mr. Sakariyah Sheu', 'position' => 'National Auditor', 'lga' => 'Ilorin East', 'priority' => 10, 'order' => 39],
            ['name' => 'Mall. Abubakar Muhammad-Jamiu', 'position' => 'National Deputy Auditor', 'lga' => 'Ilorin South', 'priority' => 11, 'order' => 40],
            ['name' => 'Alh. Abdullahi Abdulfatai Oloyin', 'position' => 'National Publicity Secretary I', 'lga' => 'Ilorin West', 'priority' => 12, 'order' => 41],
            ['name' => 'Mr. Bello Abdullateef', 'position' => 'National Publicity Secretary II', 'lga' => 'Asa', 'priority' => 13, 'order' => 42],
            ['name' => 'Mrs. Uthman T. Rasheedat Oke', 'position' => 'National Social Director', 'lga' => 'Ilorin West', 'priority' => 14, 'order' => 43],
            ['name' => 'Mr. Ibrahim Sobolaje Mustapha', 'position' => 'National Asst. Social Director', 'lga' => 'Ilorin East', 'priority' => 15, 'order' => 44],
            ['name' => 'Mr. Salman Abdulrasak Olaitan', 'position' => 'National Welfare Director', 'lga' => 'Ilorin West', 'priority' => 16, 'order' => 45],
            ['name' => 'Imam Mahamood Ibrahim Elemosho', 'position' => 'National Asst. Welfare Director', 'lga' => 'Ilorin East', 'priority' => 17, 'order' => 46],
            ['name' => 'Alh. Abdullahi Babatunde Salau', 'position' => 'Ex-Officio I', 'lga' => 'Ilorin East', 'priority' => 18, 'order' => 47],
            ['name' => 'Barr. Tajudeen Akanbi Abdullahi, Esq.', 'position' => 'Ex-Officio II', 'lga' => 'Ilorin East', 'priority' => 19, 'order' => 48],
        ];

        TeamMember::where('type', 'executive_pioneering')
            ->whereNotIn('name', array_column($pioneering, 'name'))
            ->update(['is_active' => false]);
        TeamMember::where('type', 'executive_present')
            ->whereNotIn('name', array_column($present, 'name'))
            ->update(['is_active' => false]);

        foreach ($pioneering as $member) {
            $this->upsertExecutive($member, 'executive_pioneering', 'pioneering', '2014-2024');
        }
        foreach ($present as $member) {
            $this->upsertExecutive($member, 'executive_present', 'present', '2024-date');
        }

        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['type' => $member['type'], 'name' => $member['name']],
                array_merge($this->defaults($member['name']), $member)
            );
        }
    }

    private function upsertExecutive(array $member, string $type, string $executiveType, string $term): void
    {
        $member['type'] = $type;
        $member['executive_type'] = $executiveType;
        $member['term'] = $term;
        $member['department'] = 'National Executive Committee';
        $member['location'] = ($member['lga'] ?? 'Ilorin Emirate') . ', Kwara State';
        unset($member['lga']);
        TeamMember::updateOrCreate(
            ['type' => $type, 'name' => $member['name'], 'position' => $member['position']],
            array_merge($this->defaults($member['name']), [
                'bio' => 'Member of the IEYDA National Executive Committee.',
                'achievements' => [],
                'is_active' => true,
            ], $member)
        );
    }

    private function defaults(string $name): array
    {
        return [
            'image' => 'placeholder.jpg',
            'email' => strtolower(preg_replace('/[^a-z0-9]+/i', '.', $name)) . '@ieyda.org',
            'phone' => null,
            'social_links' => [],
            'is_active' => true,
        ];
    }
}
