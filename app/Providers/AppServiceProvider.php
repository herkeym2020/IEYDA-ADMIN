<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\{Community, ContactMessage};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL
        Schema::defaultStringLength(191);
        
        // Set timezone
        date_default_timezone_set('UTC');
        
        // Share navbar data with all admin views
        View::composer('admin.layouts.app', function ($view) {
            $pendingCommunities = Community::where('status', 'pending')->count();
            $unreadMessages = ContactMessage::where('status', 'new')->count();
            $notificationCount = $pendingCommunities + $unreadMessages;
            
            $view->with(compact('pendingCommunities', 'unreadMessages', 'notificationCount'));
        });
    }
}
