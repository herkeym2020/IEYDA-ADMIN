<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;
    protected $fromNumber;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
        $this->fromNumber = config('services.whatsapp.from_number');
    }

    /**
     * Send a WhatsApp message
     */
    public function sendMessage($to, $message)
    {
        if (!$this->apiUrl || !$this->apiKey) {
            Log::warning('WhatsApp API not configured');
            return false;
        }

        // Clean phone number (remove spaces, dashes, etc.)
        $to = $this->cleanPhoneNumber($to);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent', ['to' => $to]);
                return true;
            } else {
                Log::error('WhatsApp send failed', [
                    'to' => $to,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp exception', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Clean and format phone number for WhatsApp
     */
    private function cleanPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 0, replace with country code (234 for Nigeria)
        if (substr($phone, 0, 1) === '0') {
            $phone = '234' . substr($phone, 1);
        }
        
        // If doesn't start with country code, add it
        if (substr($phone, 0, 3) !== '234') {
            $phone = '234' . $phone;
        }
        
        return $phone;
    }

    /**
     * Send welcome message to new financial member
     */
    public function sendWelcomeMessage($name, $phone)
    {
        $message = "Dear {$name},\n\n";
        $message .= "🎉 Welcome to IEYDA Financial Membership!\n\n";
        $message .= "Thank you for joining the *Collective Drive: A 5K Commitment for Community Development*.\n\n";
        $message .= "Your monthly contribution of ₦5,000 will support:\n";
        $message .= "• 💧 Empowerment for less privileged\n";
        $message .= "• ❤️ Support for widows\n";
        $message .= "• 🎓 Education & moral support\n";
        $message .= "• 📢 Community awareness programs\n\n";
        $message .= "Together, we build a stronger community! 🌟\n\n";
        $message .= "Best regards,\n*IEYDA Team*";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send monthly appreciation reminder
     */
    public function sendAppreciationReminder($name, $phone)
    {
        $message = "Dear {$name},\n\n";
        $message .= "🙏 *Thank You for Your Continued Support!*\n\n";
        $message .= "Your commitment to IEYDA's mission is making a real difference in our community.\n\n";
        $message .= "This month, your contribution helped us:\n";
        $message .= "✅ Support families in need\n";
        $message .= "✅ Provide educational resources\n";
        $message .= "✅ Build stronger communities\n\n";
        $message .= "💚 Thank you for being part of the change!\n\n";
        $message .= "*IEYDA - Building Tomorrow, Today*";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send monthly engagement reminder (for inactive members)
     */
    public function sendEngagementReminder($name, $phone)
    {
        $message = "Dear {$name},\n\n";
        $message .= "👋 We miss you at IEYDA!\n\n";
        $message .= "It's been a while since we last connected. As a valued financial member, your engagement means a lot to us.\n\n";
        $message .= "🌟 *New Updates:*\n";
        $message .= "• Recent community projects completed\n";
        $message .= "• Upcoming empowerment programs\n";
        $message .= "• Ways to get more involved\n\n";
        $message .= "📞 Let's stay connected! Reply to this message or contact us anytime.\n\n";
        $message .= "Warm regards,\n*IEYDA Team*";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder($name, $phone, $amount)
    {
        $message = "Dear {$name},\n\n";
        $message .= "🔔 *Monthly Contribution Reminder*\n\n";
        $message .= "This is a friendly reminder that your monthly commitment of ₦" . number_format($amount) . " is due.\n\n";
        $message .= "💳 *Bank Details:*\n";
        $message .= "Account: 2999563017\n";
        $message .= "Name: ILORIN EMIRATE YOUTH DEVELOPMENT ASSOCIATION\n";
        $message .= "Bank: FCMB\n\n";
        $message .= "Every contribution counts! Thank you for your continuous support. 💚\n\n";
        $message .= "*IEYDA Team*";

        return $this->sendMessage($phone, $message);
    }
}
