<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacebookWebhookController extends Controller
{
    private $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    /**
     * Handle Facebook webhook verification (GET request)
     * Facebook sends a challenge token to verify the webhook is valid
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = config('services.facebook.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken && $challenge) {
            Log::info('Facebook webhook verified successfully');
            return response($challenge, 200);
        }

        Log::warning('Facebook webhook verification failed', [
            'mode' => $mode,
            'token_match' => $token === $verifyToken,
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming Facebook webhook events (POST request)
     * Called when page gets a new post
     */
    public function webhook(Request $request)
    {
        try {
            $rawPayload = $request->getContent();
            $signature = $request->header('X-Hub-Signature-256');

            // Verify webhook signature
            if (!$this->facebookService->verifyWebhookSignature($rawPayload, $signature)) {
                Log::warning('Facebook webhook signature verification failed');
                return response()->json(['message' => 'Signature verification failed'], 403);
            }

            $data = $request->json()->all();

            Log::info('Facebook webhook received', [
                'object' => $data['object'] ?? null,
                'entries' => count($data['entry'] ?? []),
            ]);

            // Process the webhook data
            $this->facebookService->processWebhook($data);

            // Always return 200 to Facebook immediately
            return response()->json(['message' => 'ok'], 200);
        } catch (\Exception $e) {
            Log::error('Facebook webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }
}
