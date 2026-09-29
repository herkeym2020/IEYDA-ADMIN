<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            // Upcoming Events
            [
                'title' => 'Annual Youth Empowerment Summit 2024',
                'description' => 'A comprehensive summit bringing together youth leaders, entrepreneurs, and mentors from across the Ilorin Emirate for networking, skill development, and strategic planning for youth empowerment initiatives.',
                'category' => 'Empowerment',
                'event_date' => now()->addDays(30),
                'event_time' => '9:00 AM - 5:00 PM',
                'location' => 'Ilorin International Conference Center',
                'organizer' => 'IEYDA Foundation',
                'phone' => '+2348012345678',
                'attendees' => '500+',
                'registration_fee' => 'Free',
                'registration_deadline' => now()->addDays(25),
                'contact_email' => 'events@ieyda.org',
                'highlights' => [
                    'Keynote speeches from industry leaders',
                    'Interactive workshops on entrepreneurship',
                    'Networking sessions with successful alumni',
                    'Panel discussions on youth challenges',
                    'Awards ceremony for outstanding youth'
                ],
                'speakers' => [
                    'Dr. Amina Salihu - Entrepreneur & Business Coach',
                    'Engr. Musa Ibrahim - Tech Innovation Expert',
                    'Hajia Fatima Abdullahi - Women\'s Rights Advocate'
                ],
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Skills Exhibition & Job Fair',
                'description' => 'Showcase of skills acquired by program participants and job opportunities from partner organizations across various sectors.',
                'category' => 'Training',
                'event_date' => now()->addDays(45),
                'event_time' => '10:00 AM - 4:00 PM',
                'location' => 'IEYDA Skills Development Center',
                'organizer' => 'IEYDA Skills Initiative',
                'phone' => '+2348033344455',
                'attendees' => '300+',
                'registration_fee' => 'Free',
                'registration_deadline' => now()->addDays(40),
                'contact_email' => 'skills@ieyda.org',
                'highlights' => [
                    'Live demonstrations of acquired skills',
                    'Job interviews with partner companies',
                    'Career counseling sessions',
                    'Skills assessment and certification',
                    'Entrepreneurship support services'
                ],
                'speakers' => [
                    'Mr. Abdullahi Garba - HR Director, Kwara State',
                    'Mrs. Aisha Mohammed - Skills Development Expert'
                ],
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Community Health Screening Program',
                'description' => 'Free comprehensive health screening and medical consultations for community members across the Emirate.',
                'category' => 'Health',
                'event_date' => now()->addDays(60),
                'event_time' => '8:00 AM - 3:00 PM',
                'location' => 'Multiple Community Centers',
                'organizer' => 'IEYDA Health Initiative',
                'phone' => '+2348055566677',
                'attendees' => '1000+',
                'registration_fee' => 'Free',
                'registration_deadline' => now()->addDays(55),
                'contact_email' => 'health@ieyda.org',
                'highlights' => [
                    'Free medical checkups',
                    'Blood pressure and diabetes screening',
                    'Health education sessions',
                    'Vaccination programs',
                    'Nutritional counseling'
                ],
                'speakers' => [
                    'Dr. Ibrahim Suleiman - Community Health Specialist',
                    'Nurse Halima Bello - Public Health Expert'
                ],
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Digital Innovation Workshop',
                'description' => 'Hands-on workshop on emerging technologies, digital marketing, and online business development for young entrepreneurs.',
                'category' => 'Technology',
                'event_date' => now()->addDays(75),
                'event_time' => '2:00 PM - 6:00 PM',
                'location' => 'IEYDA ICT Training Center',
                'organizer' => 'IEYDA Tech Hub',
                'phone' => '+2348077788899',
                'attendees' => '150+',
                'registration_fee' => '₦2,000',
                'registration_deadline' => now()->addDays(70),
                'contact_email' => 'ict@ieyda.org',
                'highlights' => [
                    'Introduction to AI and machine learning',
                    'Digital marketing strategies',
                    'E-commerce platform development',
                    'Social media optimization',
                    'Online payment systems'
                ],
                'speakers' => [
                    'Engr. Fatima Aliyu - Software Developer',
                    'Mr. Usman Garba - Digital Marketing Expert'
                ],
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Women Empowerment Conference',
                'description' => 'Empowering women through entrepreneurship, leadership training, and financial literacy programs.',
                'category' => 'Empowerment',
                'event_date' => now()->addDays(90),
                'event_time' => '9:30 AM - 4:30 PM',
                'location' => 'Kwara State University Auditorium',
                'organizer' => 'IEYDA Women\'s Wing',
                'phone' => '+2348099900011',
                'attendees' => '400+',
                'registration_fee' => '₦1,500',
                'registration_deadline' => now()->addDays(85),
                'contact_email' => 'women@ieyda.org',
                'highlights' => [
                    'Women leadership in business',
                    'Financial literacy workshops',
                    'Microfinance opportunities',
                    'Work-life balance strategies',
                    'Networking with successful women'
                ],
                'speakers' => [
                    'Hajia Aisha Buhari - Women\'s Rights Advocate',
                    'Dr. Khadijah Ibrahim - Business Development Expert'
                ],
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Environmental Conservation Day',
                'description' => 'Community-wide tree planting, waste management, and environmental awareness activities.',
                'category' => 'Environment',
                'event_date' => now()->addDays(105),
                'event_time' => '7:00 AM - 2:00 PM',
                'location' => 'Multiple Locations Across Ilorin',
                'organizer' => 'IEYDA Green Initiative',
                'phone' => '+2348111122233',
                'attendees' => '800+',
                'registration_fee' => 'Free',
                'registration_deadline' => now()->addDays(100),
                'contact_email' => 'environment@ieyda.org',
                'highlights' => [
                    'Tree planting activities',
                    'Waste management education',
                    'Clean-up campaigns',
                    'Environmental awareness talks',
                    'Sustainable living workshops'
                ],
                'speakers' => [
                    'Prof. Abdullahi Salihu - Environmental Scientist',
                    'Mrs. Fatima Garba - Conservation Expert'
                ],
                'status' => 'upcoming',
                'is_featured' => false,
            ],

            // Past Events
            [
                'title' => 'Youth Leadership Bootcamp 2024',
                'description' => 'Intensive leadership training program for emerging youth leaders in the Emirate.',
                'category' => 'Leadership',
                'event_date' => now()->subDays(30),
                'event_time' => '9:00 AM - 5:00 PM',
                'location' => 'IEYDA Training Center',
                'organizer' => 'IEYDA Leadership Institute',
                'phone' => '+2348133344455',
                'attendees' => '200+',
                'registration_fee' => '₦5,000',
                'registration_deadline' => now()->subDays(35),
                'contact_email' => 'leadership@ieyda.org',
                'highlights' => [
                    'Leadership theory and practice',
                    'Team building exercises',
                    'Public speaking workshops',
                    'Project management skills',
                    'Mentorship program setup'
                ],
                'speakers' => [
                    'Dr. Ahmed Musa - Leadership Consultant',
                    'Mrs. Zainab Umar - Youth Development Expert'
                ],
                'outcomes' => [
                    '150 youth leaders trained',
                    '5 community projects initiated',
                    'Leadership certificates awarded',
                    'Mentorship programs established'
                ],
                'status' => 'completed',
                'is_featured' => false,
            ],
            [
                'title' => 'Skills Acquisition Graduation Ceremony',
                'description' => 'Graduation ceremony for participants who completed various skills acquisition programs.',
                'category' => 'Training',
                'event_date' => now()->subDays(45),
                'event_time' => '10:00 AM - 2:00 PM',
                'location' => 'Ilorin Civic Center',
                'organizer' => 'IEYDA Skills Academy',
                'phone' => '+2348155566677',
                'attendees' => '400+',
                'registration_fee' => 'Free',
                'registration_deadline' => now()->subDays(50),
                'contact_email' => 'graduation@ieyda.org',
                'highlights' => [
                    'Certificate presentation',
                    'Success stories sharing',
                    'Employer networking',
                    'Alumni association launch',
                    'Future opportunities discussion'
                ],
                'speakers' => [
                    'Alh. Ibrahim Sulu-Gambari - Emir of Ilorin',
                    'Hon. Minister of Youth Development',
                    'Mrs. Amina Garba - IEYDA President'
                ],
                'outcomes' => [
                    '300 graduates certified',
                    '80% employment rate achieved',
                    '50 new businesses started',
                    'Community impact recognized'
                ],
                'status' => 'completed',
                'is_featured' => true,
            ],
            [
                'title' => 'Community Health Fair 2024',
                'description' => 'Free health screening and medical services for community members.',
                'category' => 'Health',
                'event_date' => now()->subDays(60),
                'event_time' => '8:00 AM - 4:00 PM',
                'location' => 'Central Mosque Grounds',
                'organizer' => 'IEYDA Health Services',
                'phone' => '+2348177788899',
                'attendees' => '800+',
                'registration_fee' => 'Free',
                'registration_deadline' => now()->subDays(65),
                'contact_email' => 'healthfair@ieyda.org',
                'highlights' => [
                    'Comprehensive health screenings',
                    'Medical consultations',
                    'Health education seminars',
                    'Preventive care information',
                    'Health insurance guidance'
                ],
                'speakers' => [
                    'Dr. Musa Ahmed - Chief Medical Officer',
                    'Dr. Fatima Ibrahim - Public Health Specialist',
                    'Nurse Aisha Mohammed - Health Educator'
                ],
                'outcomes' => [
                    '600 people screened',
                    'Medical conditions identified',
                    'Health education provided',
                    'Follow-up care arranged'
                ],
                'status' => 'completed',
                'is_featured' => false,
            ],
            [
                'title' => 'Digital Literacy Training Program',
                'description' => 'Computer literacy and digital skills training for youth and adults.',
                'category' => 'Technology',
                'event_date' => now()->subDays(75),
                'event_time' => '2:00 PM - 6:00 PM',
                'location' => 'IEYDA ICT Center',
                'organizer' => 'IEYDA Digital Academy',
                'phone' => '+2348199900011',
                'attendees' => '120+',
                'registration_fee' => '₦3,000',
                'registration_deadline' => now()->subDays(80),
                'contact_email' => 'digital@ieyda.org',
                'highlights' => [
                    'Computer basics training',
                    'Internet safety education',
                    'Microsoft Office skills',
                    'Digital communication tools',
                    'Online job search techniques'
                ],
                'speakers' => [
                    'Mr. Usman Bello - IT Trainer',
                    'Mrs. Khadijah Ali - Digital Literacy Expert'
                ],
                'outcomes' => [
                    '100 participants trained',
                    'Basic computer skills acquired',
                    'Internet literacy improved',
                    'Digital certificates awarded'
                ],
                'status' => 'completed',
                'is_featured' => false,
            ],
        ];

        foreach ($events as $event) {
            $slug = Str::slug($event['title']);
            Event::updateOrCreate(
                ['slug' => $slug],
                array_merge($event, [
                    'slug' => $slug,
                    'image' => 'event-' . rand(1, 4) . '.jpg',
                    'registration_link' => null,
                ])
            );
        }
    }
}
