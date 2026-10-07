<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API routes for frontend (future implementation)
Route::prefix('v1')->group(function () {
    // Single aggregated bootstrap payload for fast first load
    Route::get('/bootstrap', [\App\Http\Controllers\Api\BootstrapController::class, 'show']);

    // Hero stats for homepage hero section
    Route::get('/hero-stats', function () {
        $stat = fn (string $key, string $default) => \App\Models\Setting::get('stat_'.$key, $default);
        return [
            [
                'icon' => 'Users',
                'number' => $stat('youth_associations', '200+'),
                'label' => 'Youth Associations',
                'description' => 'Registered community organizations',
            ],
            [
                'icon' => 'Target',
                'number' => $stat('youth_population', '2.3M+'),
                'label' => 'Youth Population',
                'description' => '65.5% of Ilorin Emirate population',
            ],
            [
                'icon' => 'Award',
                'number' => $stat('active_programs', '50+'),
                'label' => 'Active Programs',
                'description' => 'Ongoing community initiatives',
            ],
            [
                'icon' => 'MapPin',
                'number' => $stat('lgas_covered', '5'),
                'label' => 'LGAs Covered',
                'description' => 'Local Government Areas served',
            ],
        ];
    });
    // Profile & 2FA API
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
        Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
        Route::put('/profile/password', [\App\Http\Controllers\Api\ProfileController::class, 'updatePassword']);
        Route::post('/profile/2fa/setup', [\App\Http\Controllers\Api\ProfileController::class, 'setup2fa']);
        Route::post('/profile/2fa/enable', [\App\Http\Controllers\Api\ProfileController::class, 'enable2fa']);
        Route::post('/profile/2fa/disable', [\App\Http\Controllers\Api\ProfileController::class, 'disable2fa']);
        Route::post('/profile/email/resend', [\App\Http\Controllers\Api\ProfileController::class, 'resendVerification']);
    });
    Route::get('/hero-slides', function () {
        return \App\Http\Resources\HeroSlideResource::collection(
            \App\Models\HeroSlide::active()->get()
        );
    });

    Route::get('/news', function () {
        return \App\Http\Resources\NewsResource::collection(
            \App\Models\News::published()->get()
        );
    });

    Route::get('/events', function () {
        return \App\Http\Resources\EventResource::collection(
            \App\Models\Event::upcoming()->paginate(10)
        );
    });

    Route::get('/past-events', function () {
        return \App\Http\Resources\EventResource::collection(
            \App\Models\Event::past()->paginate(10)
        );
    });

    Route::get('/programs', function () {
        return \App\Http\Resources\ProgramResource::collection(
            \App\Models\Program::active()->get()
        );
    });

    Route::get('/team', function () {
        return \App\Http\Resources\TeamMemberResource::collection(
            \App\Models\TeamMember::active()->get()
        );
    });

    Route::get('/gallery', function () {
        return \App\Http\Resources\GalleryResource::collection(
            \App\Models\Gallery::active()->get()
        );
    });

    Route::get('/testimonials', function () {
        return \App\Http\Resources\TestimonialResource::collection(
            \App\Models\Testimonial::active()->get()
        );
    });

    Route::get('/communities', function () {
        return \App\Http\Resources\CommunityResource::collection(
            \App\Models\Community::approved()->get()
        );
    });

    Route::get('/meeting-notices', [\App\Http\Controllers\Api\FeatureContentController::class, 'notices']);
    Route::get('/monthly-realizations', [\App\Http\Controllers\Api\FeatureContentController::class, 'realizations']);
    Route::get('/history/ilorin', [\App\Http\Controllers\Api\FeatureContentController::class, 'history']);

    Route::get('/settings', function () {
        return \App\Models\Setting::whereIn('key', [
            'site_name', 'site_description', 'contact_email', 'contact_phone', 'contact_address',
            'logo_url', 'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url',
            'whatsapp_url', 'address', 'office_hours', 'office_address',
            'office_hours_weekdays', 'office_hours_saturday', 'office_hours_sunday',
            'phone_president', 'phone_secretary', 'phone_general',
            'email_general', 'email_president', 'email_secretary', 'departments',
        ])->pluck('value', 'key');
    });

    // Contact form endpoint
    Route::post('/contact', [\App\Http\Controllers\Api\ContactController::class, 'send'])->middleware('throttle:5,1');

    // Qur'an Competition registration
    Route::post('/quran-competition/register', [\App\Http\Controllers\Api\QuranCompetitionController::class, 'register'])->middleware('throttle:5,10');

    // Guest Registration for Qur'an Championship (e-invitation)
    Route::post('/guest-registration/register', [\App\Http\Controllers\Api\GuestRegistrationController::class, 'store'])->middleware('throttle:10,1');

    // ICT Programme Registration (rate limited to prevent spam)
    Route::post('/ict-programme/register', [\App\Http\Controllers\Api\IctProgrammeController::class, 'register'])->middleware('throttle:10,1');
    Route::get('/quran-competition/stats', [\App\Http\Controllers\Api\QuranCompetitionController::class, 'stats']);

    // Financial Member lead intake (Google Form webhook)
    Route::post('/financial-member/submit', [\App\Http\Controllers\Api\FinancialMemberLeadController::class, 'store'])->middleware('throttle:10,1');
    
    // Debug route to verify API is working
    Route::get('/debug', function () {
        return response()->json(['message' => 'API is working']);
    });

    // Donations
    Route::post('/donations/initiate', [\App\Http\Controllers\Api\DonationController::class, 'initiate'])->middleware('throttle:10,1');
    Route::get('/donations/paystack-callback', [\App\Http\Controllers\Api\DonationController::class, 'paystackCallback'])->name('api.donations.paystack-callback');
    Route::post('/donations/paystack-webhook', [\App\Http\Controllers\Api\DonationController::class, 'paystackWebhook'])->name('api.donations.paystack-webhook');

    // Facebook Webhook
    Route::get('/facebook/webhook', [\App\Http\Controllers\Api\FacebookWebhookController::class, 'verify'])->withoutMiddleware('api');
    Route::post('/facebook/webhook', [\App\Http\Controllers\Api\FacebookWebhookController::class, 'webhook'])->withoutMiddleware('api');

    Route::get('/communities/all', [\App\Http\Controllers\Api\CommunityController::class, 'all'])->middleware('auth:sanctum'); // admin: all
    Route::post('/communities', [\App\Http\Controllers\Api\CommunityController::class, 'store'])->middleware('throttle:5,10'); // register new
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/communities/{community}/approve', [\App\Http\Controllers\Api\CommunityController::class, 'approve']); // approve
        Route::post('/communities/{community}/decline', [\App\Http\Controllers\Api\CommunityController::class, 'decline']); // decline
        Route::delete('/communities/{community}', [\App\Http\Controllers\Api\CommunityController::class, 'destroy']);
    });
});
