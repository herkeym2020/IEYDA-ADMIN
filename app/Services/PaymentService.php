<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public static function initiateDonation(array $data): array
    {
        $provider = Config::get('payment.default', 'paystack');
        
        return match ($provider) {
            'paystack' => self::initiatePaystackDonation($data),
            default => [
                'success' => false,
                'message' => 'Unknown payment provider',
            ],
        };
    }

    private static function initiatePaystackDonation(array $data): array
    {
        $secretKey = Config::get('payment.paystack.secret_key');
        $baseUrl = Config::get('payment.paystack.base_url', 'https://api.paystack.co');

        if (!$secretKey) {
            return [
                'success' => false,
                'message' => 'Paystack secret key not configured',
            ];
        }

        try {
            // Create donation record first
            $donation = Donation::create([
                'donor_name' => $data['donor_name'] ?? null,
                'donor_email' => $data['donor_email'],
                'donor_phone' => $data['donor_phone'] ?? null,
                'amount_ngn' => $data['amount_ngn'],
                'currency' => 'NGN',
                'status' => 'pending',
                'provider' => 'paystack',
                'metadata' => $data['metadata'] ?? [],
            ]);

            // Initialize payment on Paystack
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$secretKey}",
                'Content-Type' => 'application/json',
            ])->post("{$baseUrl}/transaction/initialize", [
                'email' => $donation->donor_email,
                'amount' => $donation->amount_ngn * 100, // Paystack uses kobo
                'reference' => "DONATION-{$donation->id}-" . time(),
                'callback_url' => route('api.donations.paystack-callback'),
                'metadata' => [
                    'donation_id' => $donation->id,
                    'donor_name' => $donation->donor_name,
                    'donor_phone' => $donation->donor_phone,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $donation->update([
                    'provider_reference' => $data['data']['reference'] ?? null,
                    'provider_access_code' => $data['data']['access_code'] ?? null,
                    'authorization_url' => $data['data']['authorization_url'] ?? null,
                    'status' => 'processing',
                ]);

                return [
                    'success' => true,
                    'donation_id' => $donation->id,
                    'authorization_url' => $data['data']['authorization_url'] ?? null,
                    'access_code' => $data['data']['access_code'] ?? null,
                    'reference' => $data['data']['reference'] ?? null,
                ];
            }

            Log::error('Paystack init failed', ['response' => $response->body()]);
            return [
                'success' => false,
                'message' => 'Failed to initialize payment',
            ];
        } catch (\Throwable $e) {
            Log::error('Paystack init error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function verifyPaystackWebhook(array $data): array
    {
        $secretKey = Config::get('payment.paystack.secret_key');
        $reference = $data['reference'] ?? null;

        if (!$reference || !$secretKey) {
            return [
                'success' => false,
                'message' => 'Invalid webhook data',
            ];
        }

        try {
            $baseUrl = Config::get('payment.paystack.base_url', 'https://api.paystack.co');
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$secretKey}",
            ])->get("{$baseUrl}/transaction/verify/{$reference}");

            if ($response->successful()) {
                $txData = $response->json();
                if ($txData['data']['status'] === 'success') {
                    $donationId = $txData['data']['metadata']['donation_id'] ?? null;
                    if ($donationId) {
                        $donation = Donation::find($donationId);
                        if ($donation) {
                            $donation->update([
                                'status' => 'success',
                                'paid_at' => now(),
                            ]);
                            return [
                                'success' => true,
                                'donation_id' => $donationId,
                                'message' => 'Donation verified',
                            ];
                        }
                    }
                }
            }

            return [
                'success' => false,
                'message' => 'Could not verify payment',
            ];
        } catch (\Throwable $e) {
            Log::error('Paystack verification error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
