<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\HeroSlideResource;
use App\Http\Resources\MeetingNoticeResource;
use App\Http\Resources\MonthlyRealizationResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Community;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\HeroSlide;
use App\Models\MeetingNotice;
use App\Models\MonthlyRealization;
use App\Models\News;
use App\Models\Page;
use App\Models\Program;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BootstrapController extends Controller
{
    private const CACHE_KEY = 'bootstrap:v3';

    private const PUBLIC_SETTING_KEYS = [
        'site_name', 'site_description', 'contact_email', 'contact_phone', 'contact_address',
        'logo_url', 'facebook_url', 'twitter_url', 'instagram_url',
        'linkedin_url', 'whatsapp_url', 'address', 'office_hours', 'office_address',
        'office_hours_weekdays', 'office_hours_saturday', 'office_hours_sunday',
        'phone_president', 'phone_secretary', 'phone_general',
        'email_general', 'email_president', 'email_secretary', 'departments',
    ];

    private const STAT_DEFAULTS = [
        'youth_associations' => '200+',
        'youth_population' => '2.3M+',
        'active_programs' => '50+',
        'lgas_covered' => '5',
        'years_of_service' => '11',
        'youth_empowered' => '0',
        'communities_reached' => '0',
        'scholarships_awarded' => '0',
        'impact_generated' => '₦0+',
    ];

    public function show(Request $request)
    {
        $payload = Cache::remember(self::CACHE_KEY, now()->addSeconds(60), function () {
            $settings = Setting::whereIn('key', self::PUBLIC_SETTING_KEYS)
                ->pluck('value', 'key');
            $siteStats = $this->siteStats();
            $heroStats = [
                ['icon' => 'Users', 'number' => $siteStats['youth_associations'], 'label' => 'Youth Associations', 'description' => 'Registered community organizations'],
                ['icon' => 'Target', 'number' => $siteStats['youth_population'], 'label' => 'Youth Population', 'description' => 'Youth population served'],
                ['icon' => 'Award', 'number' => $siteStats['active_programs'], 'label' => 'Active Programs', 'description' => 'Ongoing community initiatives'],
                ['icon' => 'MapPin', 'number' => $siteStats['lgas_covered'], 'label' => 'LGAs Covered', 'description' => 'Local Government Areas served'],
            ];

            $historyPage = Page::where('slug', 'ilorin-history')->first();

            return [
                'heroSlides' => HeroSlideResource::collection(HeroSlide::active()->limit(8)->get()),
                'heroStats' => $heroStats,
                'siteStats' => $siteStats,
                'news' => NewsResource::collection(News::published()->limit(8)->get()),
                'events' => EventResource::collection(Event::upcoming()->limit(8)->get()),
                'pastEvents' => EventResource::collection(Event::past()->limit(8)->get()),
                'programs' => ProgramResource::collection(Program::active()->limit(12)->get()),
                'team' => TeamMemberResource::collection(TeamMember::active()->limit(40)->get()),
                'gallery' => GalleryResource::collection(Gallery::active()->limit(20)->get()),
                'testimonials' => TestimonialResource::collection(Testimonial::active()->limit(12)->get()),
                'communities' => Community::approved()->select(['id', 'name', 'lga', 'description'])->limit(250)->get(),
                'settings' => $settings,
                'meetingNotices' => MeetingNoticeResource::collection(MeetingNotice::popup()->orderByDesc('priority')->orderBy('starts_at')->limit(5)->get()),
                'monthlyRealizations' => MonthlyRealizationResource::collection(MonthlyRealization::latestFeatured()->limit(6)->get()),
                'history' => $historyPage ? [
                    'slug' => $historyPage->slug,
                    'title' => $historyPage->title,
                    'content' => $historyPage->content,
                    'metadata' => $historyPage->metadata ?? [],
                ] : null,
            ];
        });

        $etag = sha1(json_encode($payload));
        $response = response()->json($payload)
            ->setEtag($etag)
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=60');

        $response->isNotModified($request);
        return $response;
    }

    private function siteStats(): array
    {
        $keys = array_map(static fn ($key) => 'stat_' . $key, array_keys(self::STAT_DEFAULTS));
        $values = Setting::whereIn('key', $keys)->pluck('value', 'key');

        $stats = [];
        foreach (self::STAT_DEFAULTS as $key => $default) {
            $stats[$key] = $values->get('stat_' . $key, $default);
        }

        return $stats;
    }
}
