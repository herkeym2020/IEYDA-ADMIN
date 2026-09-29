<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\MembershipPayment;

class MembershipPaymentController extends Controller
{
    public function showPaymentPage()
    {
        return view('membership.payment');
    }

    public function initiatePayment(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1000', // Minimum 1000 Naira
        ]);

        $reference = 'IEYDA-' . strtoupper(Str::random(10));

        // Initialize Paystack payment
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $validated['email'],
            'amount' => $validated['amount'] * 100, // Convert to kobo
            'reference' => $reference,
            'callback_url' => route('financial.member.callback'),
            'metadata' => [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'custom_fields' => [
                    [
                        'display_name' => 'Membership Type',
                        'variable_name' => 'membership_type',
                        'value' => 'Financial Member'
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            // Store payment record
            MembershipPayment::create([
                'reference' => $reference,
                'email' => $validated['email'],
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'amount' => $validated['amount'],
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'authorization_url' => $response->json()['data']['authorization_url'],
                'reference' => $reference,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to initialize payment. Please try again.',
        ], 500);
    }

    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('financial.member')->with('error', 'Invalid payment reference');
        }

        // Verify payment with Paystack
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response->json()['data']['status'] === 'success') {
            $payment = MembershipPayment::where('reference', $reference)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'transaction_data' => $response->json()['data'],
                ]);

                // Generate unique token for Google Form access
                $token = Str::random(32);
                $payment->update(['form_access_token' => $token]);

                // Redirect to Google Form with token
                $googleFormUrl = config('services.google_form.url');
                $redirectUrl = $googleFormUrl . '?token=' . $token . '&email=' . urlencode($payment->email);

                return redirect($redirectUrl);
            }
        }

        return redirect()->route('financial.member')->with('error', 'Payment verification failed');
    }

    public function verifyFormAccess(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        $payment = MembershipPayment::where('form_access_token', $token)
            ->where('email', $email)
            ->where('status', 'completed')
            ->first();

        return response()->json([
            'valid' => $payment !== null,
            'message' => $payment ? 'Access granted' : 'Invalid or expired access token',
        ]);
    }
}
