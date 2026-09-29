<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMail;

class NotificationService
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send welcome notification (Email + WhatsApp)
     */
    public function sendWelcomeNotification($member)
    {
        // Send Email
        $this->sendWelcomeEmail($member);

        // Send WhatsApp if phone exists
        if ($member->phone) {
            $this->whatsappService->sendWelcomeMessage($member->name, $member->phone);
        }
    }

    /**
     * Send monthly reminder notification (Email + WhatsApp)
     */
    public function sendMonthlyReminder($member, $type = 'appreciation')
    {
        if ($type === 'appreciation') {
            // Send appreciation email
            $this->sendAppreciationEmail($member);
            
            // Send WhatsApp appreciation
            if ($member->phone) {
                $this->whatsappService->sendAppreciationReminder($member->name, $member->phone);
            }
        } else {
            // Send engagement email
            $this->sendEngagementEmail($member);
            
            // Send WhatsApp engagement
            if ($member->phone) {
                $this->whatsappService->sendEngagementReminder($member->name, $member->phone);
            }
        }
    }

    /**
     * Send payment reminder notification (Email + WhatsApp)
     */
    public function sendPaymentReminder($member, $amount)
    {
        // Send Email
        $this->sendPaymentReminderEmail($member, $amount);

        // Send WhatsApp
        if ($member->phone) {
            $this->whatsappService->sendPaymentReminder($member->name, $member->phone, $amount);
        }
    }

    /**
     * Send welcome email
     */
    private function sendWelcomeEmail($member)
    {
        $subject = '🎉 Welcome to IEYDA Financial Membership!';
        
        $body = "<h2 style='color: #2E7D32;'>Welcome to the Family, {$member->name}!</h2>";
        $body .= "<p>Thank you for becoming a financial member of the <strong>Ilorin Emirate Youths Development Association (IEYDA)</strong>.</p>";
        
        $body .= "<div style='background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%); padding: 20px; border-radius: 10px; color: white; margin: 20px 0;'>";
        $body .= "<h3 style='margin-top: 0; color: white;'>Your Commitment Makes a Difference</h3>";
        $body .= "<p>By joining our <strong>Collective Drive: A 5K Commitment for Community Development</strong>, you're supporting:</p>";
        $body .= "<ul style='list-style: none; padding-left: 0;'>";
        $body .= "<li>💧 Empowerment to the less privileged (water, environmental)</li>";
        $body .= "<li>❤️ Support for widows</li>";
        $body .= "<li>🎓 Education and moral support</li>";
        $body .= "<li>📢 Awareness and orientation programs</li>";
        $body .= "</ul>";
        $body .= "</div>";
        
        $body .= "<h3 style='color: #FFB300;'>Bank Transfer Details</h3>";
        $body .= "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #2E7D32;'>";
        $body .= "<p><strong>Account Number:</strong> 2999563017</p>";
        $body .= "<p><strong>Account Name:</strong> ILORIN EMIRATE YOUTH DEVELOPMENT ASSOCIATION</p>";
        $body .= "<p><strong>Bank:</strong> FCMB</p>";
        $body .= "</div>";
        
        $body .= "<p style='margin-top: 30px;'><em>Progress begins when responsibility is shared.</em></p>";
        $body .= "<p>Welcome aboard! 🌟</p>";
        
        Mail::to($member->email)->send(new GenericMail($subject, $body));
    }

    /**
     * Send appreciation email
     */
    private function sendAppreciationEmail($member)
    {
        $subject = '🙏 Thank You for Your Continued Support - IEYDA';
        
        $body = "<h2 style='color: #2E7D32;'>Dear {$member->name},</h2>";
        $body .= "<p>We wanted to take a moment to express our heartfelt gratitude for your continued support as a financial member of IEYDA.</p>";
        
        $body .= "<div style='background: linear-gradient(135deg, #FFB300 10%, #FFA000 100%); padding: 20px; border-radius: 10px; margin: 20px 0;'>";
        $body .= "<h3 style='margin-top: 0; color: white;'>Your Impact This Month</h3>";
        $body .= "<p style='color: white;'>Your dedication and contribution are making a real difference in our community. Together, we are building a brighter future for the youth of Ilorin Emirate.</p>";
        $body .= "</div>";
        
        $body .= "<p>Thank you for being an integral part of our mission! 💚</p>";
        $body .= "<p><strong>Warm regards,</strong><br>IEYDA Team</p>";
        
        Mail::to($member->email)->send(new GenericMail($subject, $body));
    }

    /**
     * Send engagement email
     */
    private function sendEngagementEmail($member)
    {
        $subject = '👋 We Miss You! - IEYDA Update';
        
        $body = "<h2 style='color: #1976D2;'>Dear {$member->name},</h2>";
        $body .= "<p>It's been a while since we last connected, and we wanted to reach out to you.</p>";
        
        $body .= "<div style='background: #e3f2fd; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #1976D2;'>";
        $body .= "<h3 style='margin-top: 0; color: #1976D2;'>What's New at IEYDA</h3>";
        $body .= "<ul>";
        $body .= "<li>Recent community development projects completed</li>";
        $body .= "<li>New empowerment programs launched</li>";
        $body .= "<li>Opportunities for member engagement</li>";
        $body .= "</ul>";
        $body .= "</div>";
        
        $body .= "<p>As a valued financial member, your engagement means a lot to us. We'd love to hear from you and keep you updated on our activities.</p>";
        $body .= "<p>If you have any questions, feedback, or would like to get more involved, please don't hesitate to reach out. 📞</p>";
        
        $body .= "<p><strong>Looking forward to staying connected!</strong><br>IEYDA Team</p>";
        
        Mail::to($member->email)->send(new GenericMail($subject, $body));
    }

    /**
     * Send payment reminder email
     */
    private function sendPaymentReminderEmail($member, $amount)
    {
        $subject = '🔔 Monthly Contribution Reminder - IEYDA';
        
        $body = "<h2 style='color: #2E7D32;'>Dear {$member->name},</h2>";
        $body .= "<p>This is a friendly reminder that your monthly commitment of <strong>₦" . number_format($amount) . "</strong> is due.</p>";
        
        $body .= "<div style='background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%); padding: 20px; border-radius: 10px; color: white; margin: 20px 0;'>";
        $body .= "<h3 style='margin-top: 0; color: white;'>💳 Bank Transfer Details</h3>";
        $body .= "<p style='margin: 5px 0;'><strong>Account Number:</strong> 2999563017</p>";
        $body .= "<p style='margin: 5px 0;'><strong>Account Name:</strong> ILORIN EMIRATE YOUTH DEVELOPMENT ASSOCIATION</p>";
        $body .= "<p style='margin: 5px 0;'><strong>Bank:</strong> FCMB</p>";
        $body .= "</div>";
        
        $body .= "<p>Every contribution counts and helps us continue our mission of community development and youth empowerment. 💚</p>";
        
        $body .= "<p><strong>Thank you for your continuous support!</strong><br>IEYDA Team</p>";
        
        Mail::to($member->email)->send(new GenericMail($subject, $body));
    }
}
