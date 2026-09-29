<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'required|email',
            'donor_phone' => 'nullable|string|max:40',
            'amount_ngn' => 'required|integer|min:100',
            'metadata' => 'nullable|array',
        ]);

        $result = PaymentService::initiateDonation($validated);

        if ($result['success']) {
            return response()->json([
                'message' => 'Donation initiated',
                'data' => [
                    'donation_id' => $result['donation_id'],
                    'authorization_url' => $result['authorization_url'],
                    'access_code' => $result['access_code'],
                    'reference' => $result['reference'],
                ],
            ]);
        }

        return response()->json([
            'message' => $result['message'] ?? 'Failed to initiate donation',
        ], 400);
    }

    public function paystackCallback(Request $request)
    {
        $result = PaymentService::verifyPaystackWebhook([
            'reference' => $request->query('reference'),
        ]);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    public function paystackWebhook(Request $request)
    {
        // Verify signature
        $secretKey = config('payment.paystack.secret_key');
        $signature = $request->header('X-Paystack-Signature');
        $body = $request->getContent();
        $hash = hash_hmac('sha512', $body, $secretKey);

        if ($hash !== $signature) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $data = $request->json()->all();
        $reference = $data['data']['reference'] ?? null;

        if ($reference) {
            PaymentService::verifyPaystackWebhook([
                'reference' => $reference,
            ]);
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
