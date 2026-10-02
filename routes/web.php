<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/admin/profile');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        Auth::user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;



// Auth Routes
Route::get('/admin/login', [Admin\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [Admin\AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [Admin\AuthController::class, 'logout'])->name('admin.logout');

// Password Reset Routes
Route::get('/admin/forgot-password', [Admin\AuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [Admin\AuthController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [Admin\AuthController::class, 'showResetPasswordForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [Admin\AuthController::class, 'resetPassword'])->name('admin.password.update');

// Financial Member Payment Routes (Public Access)
Route::get('/financial-member', [App\Http\Controllers\MembershipPaymentController::class, 'showPaymentPage'])->name('financial.member');
Route::post('/financial-member/payment/initiate', [App\Http\Controllers\MembershipPaymentController::class, 'initiatePayment'])->name('financial.member.initiate');
Route::get('/financial-member/payment/callback', [App\Http\Controllers\MembershipPaymentController::class, 'handleCallback'])->name('financial.member.callback');

// Admin Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // 2FA
    Route::get('/profile/2fa', [Admin\ProfileController::class, 'show2faForm'])->name('profile.2fa');
    Route::post('/profile/2fa/enable', [Admin\ProfileController::class, 'enable2fa'])->name('profile.2fa.enable');
    Route::post('/profile/2fa/disable', [Admin\ProfileController::class, 'disable2fa'])->name('profile.2fa.disable');
    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-pdf', [Admin\DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');
    Route::get('/dashboard/export-excel', [Admin\DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
    
    // Search
    Route::get('/search', [Admin\SearchController::class, 'search'])->name('search');
    
    // Drafts & Scheduling (integrated)
    Route::get('/drafts', [Admin\DraftController::class, 'index'])->name('drafts.index');
    Route::get('/drafts/scheduled', [Admin\DraftController::class, 'scheduled'])->name('drafts.scheduled');
    Route::post('/drafts/publish', [Admin\DraftController::class, 'publish'])->name('drafts.publish');
    
    // SEO Management
    Route::get('/{type}/{id}/seo', [Admin\SeoController::class, 'edit'])->name('seo.edit');
    Route::post('/{type}/{id}/seo', [Admin\SeoController::class, 'update'])->name('seo.update');
    Route::get('/{type}/{id}/seo/suggestions', [Admin\SeoController::class, 'suggestions'])->name('seo.suggestions');
    
    // Hero Slides
    Route::resource('hero-slides', Admin\HeroSlideController::class);
    
    // News
    Route::resource('news', Admin\NewsController::class);
    Route::post('news/bulk-delete', [Admin\NewsController::class, 'bulkDelete'])->name('news.bulkDelete');
    
    // Events
    Route::resource('events', Admin\EventController::class);
    Route::post('events/bulk-delete', [Admin\EventController::class, 'bulkDelete'])->name('events.bulkDelete');
    
    // Programs
    Route::resource('programs', Admin\ProgramController::class);
    Route::post('programs/bulk-delete', [Admin\ProgramController::class, 'bulkDelete'])->name('programs.bulkDelete');
    
    // Team Members
    Route::resource('team-members', Admin\TeamMemberController::class);
    Route::get('team-members/bulk-upload', [Admin\TeamMemberController::class, 'bulkUploadForm'])->name('team-members.bulkUploadForm');
    Route::post('team-members/bulk-upload', [Admin\TeamMemberController::class, 'bulkUpload'])->name('team-members.bulkUpload');
    Route::post('team-members/bulk-delete', [Admin\TeamMemberController::class, 'bulkDelete'])->name('team-members.bulkDelete');
    
    // Gallery
    Route::resource('gallery', Admin\GalleryController::class);
    
    // Testimonials
    Route::resource('testimonials', Admin\TestimonialController::class);
    Route::post('testimonials/bulk-delete', [Admin\TestimonialController::class, 'bulkDelete'])->name('testimonials.bulkDelete');

    Route::resource('meeting-notices', Admin\MeetingNoticeController::class)->except(['show']);
    Route::resource('monthly-realizations', Admin\MonthlyRealizationController::class)->except(['show']);
    
    // Settings
    Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
    // Bulk update of settings (matches the form in the view)
    Route::put('/settings/bulk-update', [Admin\SettingController::class, 'bulkUpdate'])->name('settings.bulk-update');
    
    // Profile
    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [Admin\ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::put('/profile/change-password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // User Management (Admin & Super Admin only)
    Route::resource('users', Admin\UserController::class);
});

// Admin Community Management & Contact Messages
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('communities', App\Http\Controllers\Admin\CommunityController::class);
    Route::post('communities/{community}/approve', [App\Http\Controllers\Admin\CommunityController::class, 'approve'])->name('communities.approve');
    Route::post('communities/{community}/decline', [App\Http\Controllers\Admin\CommunityController::class, 'decline'])->name('communities.decline');
    Route::post('communities/bulk-delete', [App\Http\Controllers\Admin\CommunityController::class, 'bulkDelete'])->name('communities.bulkDelete');

    // Contact Messages
    Route::get('contact-messages', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contact_message}', [App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('contact-messages/{contact_message}/reply', [App\Http\Controllers\Admin\ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::delete('contact-messages/{contact_message}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    Route::post('contact-messages/bulk-delete', [App\Http\Controllers\Admin\ContactMessageController::class, 'bulkDelete'])->name('contact-messages.bulkDelete');

    // Financial Member Leads
    Route::get('leads', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'index'])->name('leads.index');
    Route::post('leads', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'store'])->name('leads.store');
    Route::get('leads/{financial_member_lead}', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'show'])->name('leads.show');
    Route::post('leads/{financial_member_lead}/mark-contacted', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'markContacted'])->name('leads.mark-contacted');
    Route::post('leads/{lead_id}/send-email', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'sendEmail'])->name('leads.send-email');
    Route::put('leads/{financial_member_lead}/status', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::put('leads/{financial_member_lead}/tag', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'updateTag'])->name('leads.update-tag');
    Route::post('leads/{financial_member_lead}/note', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'addNote'])->name('leads.add-note');
    Route::get('leads/export', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'export'])->name('leads.export');
    Route::post('leads/bulk-delete', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'bulkDelete'])->name('leads.bulk-delete');
    Route::post('leads/bulk-tag', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'bulkTag'])->name('leads.bulk-tag');
    Route::post('leads/send-monthly-outreach', [App\Http\Controllers\Admin\FinancialMemberLeadController::class, 'sendMonthlyOutreach'])->name('leads.send-monthly-outreach');

    // Qur'an Competition Participants
    Route::get('quran-competition', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'index'])->name('quran-competition.index');
    Route::get('quran-competition/{id}', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'show'])->name('quran-competition.show');
    Route::post('quran-competition/{id}/update-status', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'updateStatus'])->name('quran-competition.update-status');
    Route::get('quran-competition-export', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'export'])->name('quran-competition.export');
    Route::delete('quran-competition/{id}', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'destroy'])->name('quran-competition.destroy');
    Route::post('quran-competition/{id}/send-email', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'sendEmail'])->name('quran-competition.send-email');
    Route::get('quran-competition/{id}/json', [App\Http\Controllers\Admin\QuranCompetitionParticipantController::class, 'getParticipant'])->name('quran-competition.json');

    // Guest Registrations (Qur'an Championship E-Invitations)
    Route::get('guest-registrations', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'index'])->name('guest-registrations.index');
    Route::get('guest-registrations/{id}', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'show'])->name('guest-registrations.show');
    Route::post('guest-registrations/{id}/update-status', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'updateStatus'])->name('guest-registrations.update-status');
    Route::post('guest-registrations/update-settings', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'updateSettings'])->name('guest-registrations.update-settings');
    Route::post('guest-registrations/bulk-action', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'bulkAction'])->name('guest-registrations.bulk-action');
    Route::get('guest-registrations/export/csv', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'exportCsv'])->name('guest-registrations.export-csv');
    Route::get('guest-registrations/export/pdf', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'exportPdf'])->name('guest-registrations.export-pdf');
    Route::delete('guest-registrations/{id}', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'destroy'])->name('guest-registrations.destroy');
    Route::post('guest-registrations/destroy-all', [App\Http\Controllers\Admin\GuestRegistrationController::class, 'destroyAll'])->name('guest-registrations.destroy-all');

    // ICT Programme Applicants
    Route::get('ict-programme', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'index'])->name('ict-programme.index');
    Route::get('ict-programme/{id}', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'show'])->name('ict-programme.show');
    Route::post('ict-programme/{id}/update-status', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'updateStatus'])->name('ict-programme.update-status');
    Route::get('ict-programme-export', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'export'])->name('ict-programme.export');
    Route::get('ict-programme/{id}/json', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'getJson'])->name('ict-programme.json');
    Route::post('ict-programme/bulk-update-status', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'bulkUpdateStatus'])->name('ict-programme.bulk-update-status');
    Route::post('ict-programme/bulk-send-message', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'bulkSendMessage'])->name('ict-programme.bulk-send-message');
    Route::post('ict-programme/bulk-verify', [App\Http\Controllers\Admin\IctProgrammeApplicantController::class, 'bulkVerify'])->name('ict-programme.bulk-verify');
});


require __DIR__.'/admin_pages.php';

// Catch-all route for React SPA (must be last)
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
Route::get('/{any?}', function () {
    // Only inject non-sensitive, public settings
    $settings = Setting::whereIn('key', [
        'site_name', 'site_description', 'contact_email', 'contact_phone', 'logo_url', 'facebook_url', 'twitter_url', 'instagram_url'
    ])->pluck('value', 'key');

    // Build a bootstrap payload with commonly used public data for instant load
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

    // Use resource transformers for consistent shape
    // Build bootstrap with safe fallbacks if columns not yet present
    try {
        $news = \App\Models\News::published()->get();
    } catch (QueryException $e) {
        $news = \App\Models\News::where('is_published', 1)->orderByDesc('published_at')->get();
    }
    try {
        $eventsUpcoming = \App\Models\Event::upcoming()->get();
        $eventsPast = \App\Models\Event::past()->get();
    } catch (QueryException $e) {
        $eventsUpcoming = \App\Models\Event::where(function($q){
            $q->whereNull('date')->orWhere('date', '>=', now());
        })->orderBy('date')->get();
        $eventsPast = \App\Models\Event::where('date', '<', now())->orderByDesc('date')->get();
    }
    try {
        $programs = \App\Models\Program::active()->get();
    } catch (QueryException $e) {
        $programs = \App\Models\Program::where('status', 'active')->get();
    }

    $bootstrap = [
        'hero-slides' => \App\Http\Resources\HeroSlideResource::collection(
            \App\Models\HeroSlide::active()->get()
        )->resolve(),
        'hero-stats' => $heroStats,
        'news' => \App\Http\Resources\NewsResource::collection($news)->resolve(),
        'events' => \App\Http\Resources\EventResource::collection($eventsUpcoming)->resolve(),
        'past-events' => \App\Http\Resources\EventResource::collection($eventsPast)->resolve(),
        'programs' => \App\Http\Resources\ProgramResource::collection($programs)->resolve(),
        'team' => \App\Http\Resources\TeamMemberResource::collection(
            \App\Models\TeamMember::active()->get()
        )->resolve(),
        'gallery' => \App\Http\Resources\GalleryResource::collection(
            \App\Models\Gallery::active()->get()
        )->resolve(),
        'testimonials' => \App\Http\Resources\TestimonialResource::collection(
            \App\Models\Testimonial::active()->get()
        )->resolve(),
        'communities' => \App\Http\Resources\CommunityResource::collection(
            \App\Models\Community::approved()->get()
        )->resolve(),
        'meeting-notices' => \App\Http\Resources\MeetingNoticeResource::collection(
            \App\Models\MeetingNotice::popup()->orderByDesc('priority')->orderBy('starts_at')->limit(5)->get()
        )->resolve(),
        'monthly-realizations' => \App\Http\Resources\MonthlyRealizationResource::collection(
            \App\Models\MonthlyRealization::latestFeatured()->limit(6)->get()
        )->resolve(),
        'history' => ($historyPage = \App\Models\Page::where('slug', 'ilorin-history')->first()) ? [
            'slug' => $historyPage->slug,
            'title' => $historyPage->title,
            'content' => $historyPage->content,
            'metadata' => $historyPage->metadata ?? [],
        ] : null,
        'settings' => $settings,
    ];

    $index = file_get_contents(public_path('index.html'));

    // Inject dynamic Open Graph & Twitter meta for share previews
    $path = request()->path();
    $currentUrl = url()->current();
    $ogTitle = $settings['site_name'] ?? 'IEYDA';
    $ogDesc = $settings['site_description'] ?? 'Empowering youth across the Ilorin Emirate.';
    $ogImage = isset($settings['logo_url']) ? asset($settings['logo_url']) : asset('storage/logo.png');
    $ogType = 'website';

    // Try to match content pages for richer previews
    $item = null;
    if (Str::startsWith($path, 'news/')) {
        $slug = Str::after($path, 'news/');
        $item = \App\Models\News::where('slug', $slug)->where('publish_status', 'published')->first();
        if ($item) {
            $ogTitle = $item->title;
            $ogDesc = $item->excerpt ?: Str::limit(strip_tags($item->content ?? ''), 160);
            $ogImage = $item->image ? asset(\Illuminate\Support\Facades\Storage::url($item->image)) : $ogImage;
            $ogType = 'article';
        }
    } elseif (Str::startsWith($path, 'events/')) {
        $slug = Str::after($path, 'events/');
        $item = \App\Models\Event::where('slug', $slug)->where('publish_status', 'published')->first();
        if ($item) {
            $ogTitle = $item->title;
            $ogDesc = $item->description ? Str::limit(strip_tags($item->description), 160) : $ogDesc;
            $ogImage = $item->image ? asset(\Illuminate\Support\Facades\Storage::url($item->image)) : $ogImage;
            $ogType = 'event';
        }
    } elseif (Str::startsWith($path, 'programs/')) {
        $slug = Str::after($path, 'programs/');
        $item = \App\Models\Program::where('slug', $slug)->where('publish_status', 'published')->first();
        if ($item) {
            $ogTitle = $item->title;
            $ogDesc = $item->summary ? Str::limit(strip_tags($item->summary), 160) : $ogDesc;
            $ogImage = $item->image ? asset(\Illuminate\Support\Facades\Storage::url($item->image)) : $ogImage;
            $ogType = 'article';
        }
    }

    $meta = "<meta property=\"og:title\" content=\"".e($ogTitle)."\">\n".
            "<meta property=\"og:description\" content=\"".e($ogDesc)."\">\n".
            "<meta property=\"og:image\" content=\"".e($ogImage)."\">\n".
            "<meta property=\"og:url\" content=\"".e($currentUrl)."\">\n".
            "<meta property=\"og:type\" content=\"".e($ogType)."\">\n".
            "<meta name=\"twitter:card\" content=\"summary_large_image\">\n".
            "<meta name=\"twitter:title\" content=\"".e($ogTitle)."\">\n".
            "<meta name=\"twitter:description\" content=\"".e($ogDesc)."\">\n".
            "<meta name=\"twitter:image\" content=\"".e($ogImage)."\">";
    $index = str_replace('</head>', $meta."\n</head>", $index);
    $settingsJson = json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    $bootstrapJson = json_encode($bootstrap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $inject = "<script>window.__APP_SETTINGS__ = $settingsJson;</script>\n".
              "<script>window.__BOOTSTRAP_DATA__ = $bootstrapJson;</script>\n".
              "</body>";
    $index = str_replace('</body>', $inject, $index);
    return response($index)->header('Content-Type', 'text/html');
})->where('any', '^(?!admin|api).*$');
