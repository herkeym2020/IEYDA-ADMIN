<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\MessageReply;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class AutoReplyService
{
    /**
     * Generate and send automatic reply for contact message
     */
    public static function sendAutoReply(ContactMessage $message): bool
    {
        try {
            // Get auto-reply template based on category
            $template = self::getTemplateForCategory($message->category);

            if (!$template) {
                return false;
            }

            // Create reply record
            $reply = MessageReply::create([
                'contact_message_id' => $message->id,
                'reply_text' => $template,
                'reply_from' => 'auto',
                'reply_template' => $message->category . '_auto_reply',
                'sent_to_user' => false,
            ]);

            // Try to send email
            try {
                \Log::info('Attempting to send auto-reply', [
                    'message_id' => $message->id,
                    'to' => $message->email,
                    'mailer' => config('mail.default'),
                ]);

                Mail::to($message->email)
                    ->view('emails.contact-auto-reply', [
                        'senderName' => $message->name,
                        'senderEmail' => $message->email,
                        'subject' => $message->subject,
                        'category' => $message->category,
                        'replyText' => $template,
                        'organizationName' => self::getOrganizationName(),
                    ])
                    ->subject('Re: ' . $message->subject . ' - Automatic Acknowledgment')
                    ->send();

                // Mark as sent
                $reply->update([
                    'sent_to_user' => true,
                    'sent_at' => now(),
                ]);

                \Log::info('Auto-reply sent successfully', ['message_id' => $message->id]);
                return true;
            } catch (\Exception $e) {
                \Log::error('Failed to send auto-reply email', [
                    'message_id' => $message->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // Still return true as the reply record was created
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Auto-reply service error', [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Get template based on message category
     */
    private static function getTemplateForCategory(string $category): ?string
    {
        $templates = [
            'general' => 'Thank you for contacting IEYDA. We have received your message and will get back to you as soon as possible. Your inquiry is important to us.',
            'membership' => 'Thank you for your interest in IEYDA membership. We are excited that you want to join our community. Our membership team will contact you shortly with all the details and next steps.',
            'volunteer' => 'Thank you for your interest in volunteering with IEYDA. We are grateful for your willingness to contribute to our mission. Our volunteer coordinator will reach out to you soon with available opportunities.',
            'events' => 'Thank you for your interest in IEYDA events. We will send you the event details and registration information shortly. We look forward to seeing you at our upcoming activities.',
            'programs' => 'Thank you for your interest in our programs. Our programs team will provide you with detailed information about the offerings that match your interests. Stay tuned!',
            'partnership' => 'Thank you for considering a partnership with IEYDA. We are interested in exploring collaboration opportunities. Our leadership team will contact you shortly to discuss potential synergies.',
        ];

        return $templates[$category] ?? $templates['general'];
    }

    /**
     * Get organization name from settings
     */
    private static function getOrganizationName(): string
    {
        try {
            $setting = Setting::where('key', 'site_name')->first();
            return $setting?->value ?? 'IEYDA';
        } catch (\Exception $e) {
            return 'IEYDA';
        }
    }

    /**
     * Send manual reply to message
     */
    public static function sendManualReply(ContactMessage $message, string $replyText): MessageReply
    {
        // Create reply record
        $reply = MessageReply::create([
            'contact_message_id' => $message->id,
            'reply_text' => $replyText,
            'reply_from' => 'admin',
            'sent_to_user' => false,
        ]);

        // Try to send email
        try {
            \Log::info('Attempting to send manual reply', [
                'message_id' => $message->id,
                'to' => $message->email,
            ]);

            Mail::view('emails.contact-admin-reply', [
                'senderName' => $message->name,
                'senderEmail' => $message->email,
                'subject' => $message->subject,
                'replyText' => $replyText,
                'organizationName' => self::getOrganizationName(),
            ])->send(new \Illuminate\Mail\Message(function ($mail) use ($message) {
                $mail->to($message->email)
                    ->subject('Re: ' . $message->subject);
            }));

            // Mark as sent
            $reply->update([
                'sent_to_user' => true,
                'sent_at' => now(),
            ]);

            \Log::info('Manual reply sent successfully', ['message_id' => $message->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to send admin reply email', [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $reply;
    }
}
