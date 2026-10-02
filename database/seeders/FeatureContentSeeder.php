<?php

namespace Database\Seeders;

use App\Models\MeetingNotice;
use App\Models\MonthlyRealization;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FeatureContentSeeder extends Seeder
{
    public function run(): void
    {
        MeetingNotice::updateOrCreate(
            ['title' => 'IEYDA National Executive Committee Meeting'],
            [
                'meeting_type' => 'Weekly NEC Meeting',
                'summary' => 'The National Executive Committee will meet to review current programmes, community priorities, and upcoming activities.',
                'details' => 'Members are encouraged to arrive early with committee updates and action points.',
                'starts_at' => Carbon::parse('2026-10-10 10:00:00'),
                'ends_at' => Carbon::parse('2026-10-10 13:00:00'),
                'location' => 'IEYDA National Secretariat, Aishat Adepate House, Edun Street, Ilorin',
                'action_label' => 'View meeting details',
                'action_url' => url('/history/ilorin'),
                'is_active' => true,
                'show_popup' => true,
                'priority' => 10,
            ]
        );

        MonthlyRealization::updateOrCreate(
            ['month' => '2026-09-01'],
            [
                'title' => 'Community infrastructure advocacy and support',
                'community_name' => 'IEYDA Community Network',
                'lga' => 'Ilorin Emirate',
                'summary' => 'This month, IEYDA celebrates the community associations and volunteers advancing practical development across the five LGAs.',
                'details' => 'The recognition reflects IEYDA\'s long-standing commitment to community development, advocacy, youth empowerment, and the facilitation of basic infrastructure such as boreholes and solar street lights.',
                'impact_metric' => 'Five LGAs represented',
                'is_active' => true,
                'priority' => 10,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'ilorin-history'],
            [
                'title' => 'Ilorin in History',
                'content' => 'A short journey through the people, institutions, and shared responsibility that continue to shape the Ilorin Emirate community.',
                'metadata' => [
                    'eyebrow' => 'A living heritage',
                    'intro' => 'From a community rooted in culture and harmony to a youth-led network working across five Local Government Areas, this is a concise IEYDA timeline.',
                    'stats' => [
                        ['label' => 'IEYDA incorporated', 'value' => '2014'],
                        ['label' => 'LGAs united', 'value' => '5'],
                        ['label' => 'Member associations', 'value' => '200+'],
                    ],
                    'timeline' => [
                        ['year' => '2014', 'title' => 'IEYDA is incorporated', 'description' => 'IEYDA was registered as an incorporated trustees under Nigerian law after its introduction to the Corporate Affairs Commission by His Royal Highness, Alhaji (Dr) Ibrahim Sulu Gambari, CFR, the Emir of Ilorin.'],
                        ['year' => '2014–2024', 'title' => 'The pioneering decade', 'description' => 'The pioneer executive committee built the association\'s foundation as a leading voice for youth development, cultural preservation, and community programmes.'],
                        ['year' => '2024', 'title' => 'A new executive chapter', 'description' => 'A new National Executive Committee took responsibility for carrying the association\'s mission forward with representation from Asa, Ilorin East, Ilorin South, Ilorin West, and Moro.'],
                        ['year' => 'Today', 'title' => 'One Emirate, shared responsibility', 'description' => 'IEYDA continues to connect more than 200 youth development associations through advocacy, education, empowerment, peacebuilding, and practical community development.'],
                    ],
                ],
            ]
        );
    }
}
