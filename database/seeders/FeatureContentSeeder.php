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
                'action_label' => 'View IEYDA community',
                'action_url' => url('/community'),
                'is_active' => true,
                'show_popup' => true,
                'priority' => 10,
            ]
        );

        MonthlyRealization::updateOrCreate(
            ['month' => '2026-10-01'],
            [
                'title' => 'Community of the Month: practical service and local leadership',
                'community_name' => 'Adewole Youth Development Association',
                'lga' => 'Ilorin East',
                'summary' => 'IEYDA celebrates the community association that demonstrates consistent service, cooperation, and measurable improvement in the lives of residents.',
                'details' => 'The Community of the Month recognition is not advertising. It is a transparent appreciation of associations working within the five LGAs through advocacy, youth development, peacebuilding, welfare, and practical community action.',
                'impact_metric' => 'Recognition for service, cooperation and measurable impact',
                'image' => null,
                'is_active' => true,
                'priority' => 10,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'ilorin-history'],
            [
                'title' => 'Ilorin: a living crossroads',
                'content' => 'A concise journey through the people, faith traditions, leadership, and shared culture that shaped Ilorin and the Ilorin Emirate.',
                'metadata' => [
                    'eyebrow' => 'A living heritage',
                    'intro' => 'Ilorin is a historic meeting point of Yoruba, Fulani, Hausa and wider Muslim scholarly traditions. Its story is one of movement, learning, leadership, resilience and harmony.',
                    'stats' => [
                        ['label' => 'Emirate LGAs', 'value' => '5'],
                        ['label' => 'IEYDA founded', 'value' => '2014'],
                        ['label' => 'Shared motto', 'value' => 'Love & Harmony'],
                    ],
                    'gallery' => [
                        ['image' => '/history/ilorin-1.jpeg', 'caption' => 'Ilorin today: a city shaped by community and movement.', 'alt' => 'Ilorin cityscape'],
                        ['image' => '/history/ilorin-2.jpeg', 'caption' => 'The visual language of an enduring Emirate.', 'alt' => 'Ilorin cultural scene'],
                        ['image' => '/history/emir-ilorin.jpg', 'caption' => 'The Emirate institution and its living leadership.', 'alt' => 'Emir of Ilorin'],
                    ],
                    'timeline' => [
                        ['year' => 'Late 18th century', 'title' => 'A Yoruba-founded settlement', 'description' => 'Ilorin began as a settlement founded by Yoruba people. It grew into a kingdom and a strategic meeting point between northern and southern trade routes.', 'image' => '/history/ilorin-1.jpeg'],
                        ['year' => '1817', 'title' => 'A turning point in the Oyo world', 'description' => 'Kakanfo Afonja, the Oyo commander at Ilorin, led a rebellion that destabilised the unity of the Oyo Empire. Mallam Alimi, Fulani warriors, Hausa communities and local political forces became part of the changing story.', 'image' => '/history/ilorin-2.jpeg'],
                        ['year' => 'c. 1829', 'title' => 'The Ilorin Emirate takes shape', 'description' => 'After Afonja’s fall, Alimi’s son Abd al-Salam became emir and pledged allegiance to the Sokoto Caliphate. Ilorin’s identity developed through a distinctive blend of Yoruba foundations, Islamic scholarship and Fulani leadership.', 'image' => '/history/emir-ilorin.jpg'],
                        ['year' => '19th century', 'title' => 'A centre of trade, learning and influence', 'description' => 'Ilorin became a major trade centre linking the Hausa north and Yoruba south. Its people sustained traditions of Islamic learning, craftsmanship, commerce and community organisation.', 'image' => '/history/ilorin-3.jpg'],
                        ['year' => '1897–1900', 'title' => 'A new administrative era', 'description' => 'Britain recognised Ilorin’s supremacy in 1897, and the city later became part of the Northern Nigeria Protectorate in 1900. The Emirate institution continued as an important centre of traditional leadership.', 'image' => '/history/ilorin-4.jpeg'],
                        ['year' => 'Today', 'title' => 'A modern educational and cultural centre', 'description' => 'Modern Ilorin is the capital of Kwara State and a commercial, industrial and educational centre. Its five Emirate LGAs—Asa, Ilorin East, Ilorin South, Ilorin West and Moro—remain connected by shared heritage.', 'image' => '/history/ilorin-1.jpeg'],
                        ['year' => '2014–date', 'title' => 'Young people carry the story forward', 'description' => 'IEYDA was incorporated in 2014 after its introduction to the Corporate Affairs Commission by His Royal Highness, Alhaji (Dr.) Ibrahim Sulu Gambari, CFR, the Emir of Ilorin. The association gives contemporary youth leadership a platform for development, cultural preservation and community service.', 'image' => '/history/emir-ilorin.jpg'],
                    ],
                    'people' => [
                        ['name' => 'Kakanfo Afonja', 'role' => 'Military commander at Ilorin', 'description' => 'A central figure in the political transformation of Ilorin and the wider Oyo world in the early nineteenth century.'],
                        ['name' => 'Mallam Alimi', 'role' => 'Scholar and spiritual leader', 'description' => 'A Fulani scholar from Sokoto whose presence and followers shaped the emergence of Ilorin as a Muslim emirate.'],
                        ['name' => 'Abd al-Salam', 'role' => 'Early Emir of Ilorin', 'description' => 'Alimi’s son, who consolidated the emirate and pledged allegiance to the Sokoto Caliphate around 1829.'],
                        ['name' => 'His Royal Highness Alhaji (Dr.) Ibrahim Sulu Gambari, CFR', 'role' => 'Emir of Ilorin and IEYDA Grand Patron', 'description' => 'A contemporary custodian of the Emirate institution and patron of youth development across Ilorin.'],
                    ],
                    'sources' => [
                        ['label' => 'Encyclopaedia Britannica: Ilorin', 'url' => 'https://www.britannica.com/place/Ilorin'],
                        ['label' => 'IEYDA at a Glance', 'url' => '/about'],
                    ],
                ],
            ]
        );
    }
}
