<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Event;
use App\Models\Program;
use App\Models\TeamMember;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\Community;
use App\Models\Setting;
use App\Models\MeetingNotice;
use App\Models\MonthlyRealization;
use App\Models\Page;

use App\Http\Resources\HeroSlideResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\TestimonialResource;
use App\Http\Resources\MeetingNoticeResource;
use App\Http\Resources\MonthlyRealizationResource;

class BootstrapController extends Controller
{
    public function show(Request $request)
    {
        $payload = Cache::remember('bootstrap:v2', now()->addSeconds(60), function () {
            $heroSlides = HeroSlideResource::collection(HeroSlide::active()->get());

            // Static hero stats for now; can be made dynamic later
            $heroStats = [
                [
                    'icon' => 'Users',
                    'number' => '200+',
                    'label' => 'Youth Associations',
                    'description' => 'Registered community organizations',
                ],
                [
                    'icon' => 'Target',
                    'number' => '2.3M+',
                    'label' => 'Youth Population',
                    'description' => '65.5% of Ilorin Emirate population',
                ],
                [
                    'icon' => 'Award',
                    'number' => '50+',
                    'label' => 'Active Programs',
                    'description' => 'Ongoing community initiatives',
                ],
                [
                    'icon' => 'MapPin',
                    'number' => '5',
                    'label' => 'LGAs Covered',
                    'description' => 'Local Government Areas served',
                ],
            ];

            $news = NewsResource::collection(News::published()->limit(8)->get());
            $events = EventResource::collection(Event::upcoming()->limit(8)->get());
            $pastEvents = EventResource::collection(Event::past()->limit(8)->get());
            $programs = ProgramResource::collection(Program::active()->get());
            $team = TeamMemberResource::collection(TeamMember::active()->get());
            $gallery = GalleryResource::collection(Gallery::active()->limit(20)->get());
            $testimonials = TestimonialResource::collection(Testimonial::active()->limit(12)->get());
            $communities = Community::approved()->select(['id','name','lga','description'])->get();
            $settings = Setting::query()->get()->pluck('value', 'key');
            $meetingNotices = MeetingNoticeResource::collection(MeetingNotice::popup()->orderByDesc('priority')->orderBy('starts_at')->limit(5)->get());
            $monthlyRealizations = MonthlyRealizationResource::collection(MonthlyRealization::latestFeatured()->limit(6)->get());
            $historyPage = Page::where('slug', 'ilorin-history')->first();

            return [
                'heroSlides' => $heroSlides,
                'heroStats' => $heroStats,
                'news' => $news,
                'events' => $events,
                'pastEvents' => $pastEvents,
                'programs' => $programs,
                'team' => $team,
                'gallery' => $gallery,
                'testimonials' => $testimonials,
                'communities' => $communities,
                'settings' => $settings,
                'meetingNotices' => $meetingNotices,
                'monthlyRealizations' => $monthlyRealizations,
                'history' => $historyPage ? [
                    'slug' => $historyPage->slug,
                    'title' => $historyPage->title,
                    'content' => $historyPage->content,
                    'metadata' => $historyPage->metadata ?? [],
                ] : null,
            ];
        });

        // Compute ETag based on payload content
        $etag = sha1(json_encode($payload));

        $response = response()->json($payload)
            ->setEtag($etag)
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=60');

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
