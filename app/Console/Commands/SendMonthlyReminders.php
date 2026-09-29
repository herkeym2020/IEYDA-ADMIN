<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FinancialMemberLead;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendMonthlyReminders extends Command
{
    protected $signature = 'members:send-monthly-reminders';
    protected $description = 'Send monthly appreciation and reminder emails/WhatsApp to financial members';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $this->info('Starting monthly reminders...');

        // Get all converted members (active financial members)
        $members = FinancialMemberLead::where('status', 'converted')
            ->whereNotNull('email')
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($members as $member) {
            try {
                // Determine message type based on last interaction
                $daysSinceInteraction = $member->last_interaction_at 
                    ? Carbon::parse($member->last_interaction_at)->diffInDays(now())
                    : 30;

                $type = $daysSinceInteraction >= 60 ? 'engagement' : 'appreciation';

                // Send notification (Email + WhatsApp)
                $this->notificationService->sendMonthlyReminder($member, $type);

                $member->logActivity('monthly_notification_sent', "Monthly {$type} notification sent (Email + WhatsApp)");
                $sent++;
                
            } catch (\Exception $e) {
                $this->error("Failed to send to {$member->email}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("Completed! Sent: {$sent}, Failed: {$failed}");
    }
}
