<?php

namespace App\Exports;

use App\Models\{Event, Gallery, HeroSlide, News, Program, TeamMember, Testimonial, Community, ContactMessage};
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardStatsExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function collection()
    {
        $weekAgo = Carbon::now()->subDays(7);
        $monthAgo = Carbon::now()->subDays(30);

        return collect([
            ['Content Statistics', '', '', ''],
            ['News Articles', News::count(), News::where('created_at', '>=', $weekAgo)->count(), News::where('created_at', '>=', $monthAgo)->count()],
            ['Events', Event::count(), Event::where('created_at', '>=', $weekAgo)->count(), Event::where('created_at', '>=', $monthAgo)->count()],
            ['Programs', Program::count(), '-', '-'],
            ['Team Members', TeamMember::count(), '-', '-'],
            ['Gallery Images', Gallery::count(), '-', '-'],
            ['Testimonials', Testimonial::count(), '-', Testimonial::where('created_at', '>=', $monthAgo)->count()],
            ['Communities', Community::count(), Community::where('created_at', '>=', $weekAgo)->count(), '-'],
            ['Hero Slides', HeroSlide::count(), '-', '-'],
            ['', '', '', ''],
            ['Messages & Pending', '', '', ''],
            ['Contact Messages', ContactMessage::count(), ContactMessage::where('created_at', '>=', $weekAgo)->count(), '-'],
            ['Unread Messages', ContactMessage::where('status', 'new')->count(), '-', '-'],
            ['Pending Communities', Community::where('status', 'pending')->count(), '-', '-'],
            ['', '', '', ''],
            ['Report Generated:', now()->format('Y-m-d H:i:s'), '', ''],
        ]);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Total',
            'Last 7 Days',
            'Last 30 Days'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            2 => ['font' => ['bold' => true]],
            12 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Dashboard Statistics';
    }
}
