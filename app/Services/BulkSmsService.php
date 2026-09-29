<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BulkSmsService
{
    public static function sendSingle(string $to, string $body): array
    {
        $provider = Config::get('sms.provider', 'log');
        return match ($provider) {
            'termii' => self::sendViaTermii($to, $body),
            'twilio' => self::sendViaTwilio($to, $body),
            default => self::sendViaLog($to, $body),
        };
    }

    public static function sendBulk(array $recipients, string $body): array
    {
        $results = [];
        foreach ($recipients as $to) {
            $results[$to] = self::sendSingle($to, $body);
        }
        return $results;
    }

    private static function sendViaLog(string $to, string $body): array
    {
        Log::info('SMS (log provider) sent', [
            'to' => $to,
            'body' => $body,
        ]);
        return [
            'success' => true,
            'provider' => 'log',
            'provider_message_id' => null,
            'error' => null,
        ];
    }

    private static function sendViaTermii(string $to, string $body): array
    {
        $apiKey = Config::get('sms.termii.api_key');
        $senderId = Config::get('sms.termii.sender_id');
        $baseUrl = rtrim(Config::get('sms.termii.base_url', 'https://api.ng.termii.com'), '/');

        if (!$apiKey || !$senderId) {
            return [
                'success' => false,
                'provider' => 'termii',
                'provider_message_id' => null,
                'error' => 'Missing TERMII_API_KEY or TERMII_SENDER_ID',
            ];
        }

        try {
            $response = Http::asJson()->post($baseUrl . '/api/sms/send', [
                'api_key' => $apiKey,
                'to' => $to,
                'from' => $senderId,
                'sms' => $body,
                'type' => 'plain',
                'channel' => 'generic',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'provider' => 'termii',
                    'provider_message_id' => $data['message_id'] ?? null,
                    'error' => null,
                ];
            }

            return [
                'success' => false,
                'provider' => 'termii',
                'provider_message_id' => null,
                'error' => $response->body(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'provider' => 'termii',
                'provider_message_id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    private static function sendViaTwilio(string $to, string $body): array
    {
        $sid = Config::get('sms.twilio.sid');
        $token = Config::get('sms.twilio.auth_token');
        $from = Config::get('sms.twilio.from');
        $messagingServiceSid = Config::get('sms.twilio.messaging_service_sid');

        if (!$sid || !$token || (!$from && !$messagingServiceSid)) {
            return [
                'success' => false,
                'provider' => 'twilio',
                'provider_message_id' => null,
                'error' => 'Missing Twilio credentials or sender',
            ];
        }

        try {
            $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
            $payload = [
                'To' => $to,
                'Body' => $body,
            ];
            if ($messagingServiceSid) {
                $payload['MessagingServiceSid'] = $messagingServiceSid;
            } else {
                $payload['From'] = $from;
            }

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'provider' => 'twilio',
                    'provider_message_id' => $data['sid'] ?? null,
                    'error' => null,
                ];
            }

            return [
                'success' => false,
                'provider' => 'twilio',
                'provider_message_id' => null,
                'error' => $response->body(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'provider' => 'twilio',
                'provider_message_id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
