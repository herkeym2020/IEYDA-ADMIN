<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Event, Gallery, HeroSlide, News, Program, TeamMember, Testimonial, Community, ContactMessage};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardStatsExport;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $stats = [
            'hero_slides' => HeroSlide::count(),
            'news' => News::count(),
            'events' => Event::count(),
            'programs' => Program::count(),
            'team_members' => TeamMember::count(),
            'gallery_images' => Gallery::count(),
            'testimonials' => Testimonial::count(),
            'communities' => Community::count(),
            'contact_messages' => ContactMessage::count(),
            'pending_communities' => Community::where('status', 'pending')->count(),
            'unread_messages' => ContactMessage::where('status', 'new')->count(),
        ];

        // Weekly stats (last 7 days)
        $weekAgo = Carbon::now()->subDays(7);
        $weeklyStats = [
            'news' => News::where('created_at', '>=', $weekAgo)->count(),
            'events' => Event::where('created_at', '>=', $weekAgo)->count(),
            'communities' => Community::where('created_at', '>=', $weekAgo)->count(),
            'messages' => ContactMessage::where('created_at', '>=', $weekAgo)->count(),
        ];

        // Monthly stats (last 30 days)
        $monthAgo = Carbon::now()->subDays(30);
        $monthlyStats = [
            'news' => News::where('created_at', '>=', $monthAgo)->count(),
            'events' => Event::where('created_at', '>=', $monthAgo)->count(),
            'testimonials' => Testimonial::where('created_at', '>=', $monthAgo)->count(),
        ];

        // Chart data - Last 7 days activity
        $last7Days = [];
        $newsData = [];
        $eventsData = [];
        $messagesData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('M d');
            
            $newsData[] = News::whereDate('created_at', $date->toDateString())->count();
            $eventsData[] = Event::whereDate('created_at', $date->toDateString())->count();
            $messagesData[] = ContactMessage::whereDate('created_at', $date->toDateString())->count();
        }

        $chartData = [
            'labels' => $last7Days,
            'news' => $newsData,
            'events' => $eventsData,
            'messages' => $messagesData,
        ];

        // Recent content
        $recentNews = News::latest()->take(5)->get();
        $upcomingEvents = Event::upcoming()->take(5)->get();
        $recentCommunities = Community::latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        // Content distribution by category
        $newsCategories = News::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        
        $eventCategories = Event::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'weeklyStats',
            'monthlyStats',
            'chartData',
            'recentNews',
            'upcomingEvents',
            'recentCommunities',
            'recentMessages',
            'newsCategories',
            'eventCategories'
        ));
    }

    public function exportPdf()
    {
        $stats = $this->getDashboardStats();
        
        $pdf = Pdf::loadView('admin.exports.dashboard-pdf', $stats);
        
        return $pdf->download('dashboard-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new DashboardStatsExport(), 'dashboard-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function getDashboardStats()
    {
        // Basic stats
        $stats = [
            'hero_slides' => HeroSlide::count(),
            'news' => News::count(),
            'events' => Event::count(),
            'programs' => Program::count(),
            'team_members' => TeamMember::count(),
            'gallery_images' => Gallery::count(),
            'testimonials' => Testimonial::count(),
            'communities' => Community::count(),
            'contact_messages' => ContactMessage::count(),
            'pending_communities' => Community::where('status', 'pending')->count(),
            'unread_messages' => ContactMessage::where('status', 'new')->count(),
        ];

        // Weekly stats
        $weekAgo = Carbon::now()->subDays(7);
        $weeklyStats = [
            'news' => News::where('created_at', '>=', $weekAgo)->count(),
            'events' => Event::where('created_at', '>=', $weekAgo)->count(),
            'communities' => Community::where('created_at', '>=', $weekAgo)->count(),
            'messages' => ContactMessage::where('created_at', '>=', $weekAgo)->count(),
        ];

        // Monthly stats
        $monthAgo = Carbon::now()->subDays(30);
        $monthlyStats = [
            'news' => News::where('created_at', '>=', $monthAgo)->count(),
            'events' => Event::where('created_at', '>=', $monthAgo)->count(),
            'testimonials' => Testimonial::where('created_at', '>=', $monthAgo)->count(),
        ];

        return compact('stats', 'weeklyStats', 'monthlyStats');
    }
}
