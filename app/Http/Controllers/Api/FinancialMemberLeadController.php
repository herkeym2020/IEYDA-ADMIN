<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialMemberLead;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class FinancialMemberLeadController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function store(Request $request)
    {
        // Debug: Log all incoming requests
        \Log::info('Financial lead API called', [
            'path' => $request->path(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'headers' => $request->headers->all(),
        ]);

        // Optional webhook token verification for security
        $expectedToken = env('LEAD_WEBHOOK_TOKEN');
        if ($expectedToken && trim($expectedToken)) {
            $provided = $request->header('X-Webhook-Token');
            \Log::info('Token check', [
                'expected_length' => strlen(trim($expectedToken)),
                'provided_length' => $provided ? strlen($provided) : 0,
                'match' => $provided && hash_equals(trim($expectedToken), $provided),
            ]);
            if (!$provided || !hash_equals(trim($expectedToken), $provided)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:40',
            'source_form_id' => 'nullable|string|max:128',
            'form_submitted_at' => 'nullable|date',
            'data' => 'nullable|array',
        ]);

        $lead = FinancialMemberLead::create([
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'source_form_id' => $validated['source_form_id'] ?? null,
            'form_submitted_at' => $validated['form_submitted_at'] ?? now(),
            'data' => $validated['data'] ?? $request->all(),
            'status' => 'new',
        ]);

        try {
            // Send welcome email and WhatsApp
            $this->notificationService->sendWelcomeNotification($lead);
            $lead->update([
                'ack_sent_at' => now(),
            ]);
            $lead->logActivity('welcome_notification_sent', 'Welcome email and WhatsApp sent to new member');
        } catch (\Throwable $e) {
            \Log::warning('Lead welcome notification failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Lead captured',
            'data' => $lead,
        ]);
    }
}
