<?php

namespace App\Http\Controllers\Admin;

use App\Models\FinancialMemberLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FinancialMemberLeadController
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $tag = $request->query('tag');
        $search = $request->query('search');

        $query = FinancialMemberLead::orderBy('created_at', 'desc');

        // Filters
        if ($status) {
            $query->where('status', $status);
        }
        if ($tag) {
            $query->where('tag', $tag);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => FinancialMemberLead::count(),
            'new' => FinancialMemberLead::where('status', 'new')->count(),
            'contacted' => FinancialMemberLead::where('status', 'contacted')->count(),
            'qualified' => FinancialMemberLead::where('status', 'qualified')->count(),
            'converted' => FinancialMemberLead::where('status', 'converted')->count(),
        ];

        // Safely get tags (handles missing column during migration)
        $tags = collect();
        try {
            $tags = FinancialMemberLead::distinct()->pluck('tag')->filter()->values();
        } catch (\Throwable $e) {
            // Column doesn't exist yet - migrations not run
            \Log::warning('Tag column not found. Please run: php artisan migrate');
        }

        return view('admin.financial_member_leads.index', [
            'leads' => $leads,
            'status' => $status,
            'tag' => $tag,
            'search' => $search,
            'stats' => $stats,
            'tags' => $tags,
        ]);
    }

    public function show(FinancialMemberLead $lead)
    {
        $lead->load('activities');
        return response()->json($lead);
    }

    public function updateStatus(FinancialMemberLead $lead, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted',
        ]);

        $oldStatus = $lead->status;
        $lead->update($validated);
        $lead->logActivity('status_changed', "Status changed from {$oldStatus} to {$validated['status']}");

        return redirect()->back()->with('success', 'Status updated');
    }

    public function updateTag(FinancialMemberLead $lead, Request $request)
    {
        $validated = $request->validate([
            'tag' => 'nullable|in:bronze,silver,gold,premium',
        ]);

        $lead->setTag($validated['tag'] ?? null);

        return redirect()->back()->with('success', 'Tag updated');
    }

    public function addNote(FinancialMemberLead $lead, Request $request)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:5000',
        ]);

        $lead->addNote($validated['notes']);

        return redirect()->back()->with('success', 'Note added');
    }

    public function markContacted(FinancialMemberLead $lead)
    {
        $lead->markContacted();
        return redirect()->back()->with('success', 'Lead marked as contacted');
    }

    public function sendEmail($lead_id, Request $request)
    {
        $lead = FinancialMemberLead::findOrFail($lead_id);
        
        if (!$lead->email) {
            return redirect()->back()->with('error', 'Lead does not have a valid email address');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        try {
            Mail::raw($validated['body'], function ($message) use ($lead, $validated) {
                $message->to($lead->email)
                        ->subject($validated['subject']);
            });

            $lead->markContacted();
            $lead->logActivity('email_sent', "Email sent: {$validated['subject']}");

            return redirect()->back()->with('success', 'Email sent successfully');
        } catch (\Throwable $e) {
            \Log::error('Failed to send email to lead', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $status = $request->query('status');
        $tag = $request->query('tag');

        $query = FinancialMemberLead::orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }
        if ($tag) {
            $query->where('tag', $tag);
        }

        $leads = $query->get();

        $filename = 'financial_leads_' . now()->format('Y-m-d_His') . '.csv';
        $file = fopen('php://memory', 'w');

        fputcsv($file, ['Name', 'Email', 'Phone', 'Status', 'Tag', 'Interactions', 'Submitted', 'Last Interaction']);

        foreach ($leads as $lead) {
            fputcsv($file, [
                $lead->name,
                $lead->email,
                $lead->phone,
                $lead->status,
                $lead->tag,
                $lead->interaction_count,
                $lead->form_submitted_at?->format('Y-m-d H:i'),
                $lead->last_interaction_at?->format('Y-m-d H:i'),
            ]);
        }

        rewind($file);
        $csv = stream_get_contents($file);
        fclose($file);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:financial_member_leads,id',
        ]);

        FinancialMemberLead::whereIn('id', $validated['ids'])->delete();

        return redirect()->back()->with('success', 'Leads deleted successfully');
    }

    public function bulkTag(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:financial_member_leads,id',
            'tag' => 'nullable|in:bronze,silver,gold,premium',
        ]);

        FinancialMemberLead::whereIn('id', $validated['ids'])->update(['tag' => $validated['tag']]);

        return redirect()->back()->with('success', 'Tags updated successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:financial_member_leads,email',
            'phone' => 'nullable|string|max:20',
            'tag' => 'nullable|in:bronze,silver,gold,premium',
            'status' => 'nullable|in:new,contacted,qualified,converted',
            'notes' => 'nullable|string|max:1000',
        ], [
            'email.unique' => 'This email is already registered.'
        ]);

        try {
            $lead = FinancialMemberLead::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'tag' => $validated['tag'] ?? null,
                'status' => $validated['status'] ?? 'new',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Log the activity
            $lead->logActivity('member_added_manually', "Member {$lead->name} added manually");

            return redirect()->back()->with('success', "Member '{$lead->name}' added successfully!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding member: ' . $e->getMessage());
        }
    }

    public function sendMonthlyOutreach(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'nullable|exists:financial_member_leads,id',
            'contact_method' => 'required|in:email,whatsapp,both',
            'message_type' => 'required|in:appreciate,reminder,custom',
            'custom_subject' => 'required_if:message_type,custom|nullable|string|max:255',
            'custom_message' => 'required_if:message_type,custom|nullable|string',
            'log_as_activity' => 'nullable|boolean',
        ]);

        try {
            $leadId = $validated['lead_id'] ?? request()->input('lead_id');
            $lead = FinancialMemberLead::findOrFail($leadId);

            $messages = [
                'appreciate' => [
                    'subject' => 'Thank You for Your Support!',
                    'message' => "Hello {$lead->name},\n\nWe wanted to take a moment to express our heartfelt gratitude for your generous support and contribution to IEYDA.\n\nYour commitment makes a real difference in what we do. Thank you for being part of our community!\n\nBest regards,\nThe IEYDA Team"
                ],
                'reminder' => [
                    'subject' => 'Membership Status Update',
                    'message' => "Hello {$lead->name},\n\nThis is a friendly reminder about your membership status with IEYDA.\n\nCurrent Status: {$lead->status}\nMembership Tier: {$lead->tag}\n\nIf you have any questions, please don't hesitate to reach out.\n\nBest regards,\nThe IEYDA Team"
                ],
                'custom' => [
                    'subject' => $validated['custom_subject'],
                    'message' => $validated['custom_message']
                ]
            ];

            $selectedMessage = $messages[$validated['message_type']];
            $contactMethod = $validated['contact_method'];
            $logAsActivity = $validated['log_as_activity'] ?? false;

            // Send Email
            if (in_array($contactMethod, ['email', 'both'])) {
                try {
                    Mail::raw($selectedMessage['message'], function ($msg) use ($lead, $selectedMessage) {
                        $msg->to($lead->email)
                            ->subject($selectedMessage['subject']);
                    });
                } catch (\Exception $e) {
                    \Log::error("Email send failed for lead {$lead->id}: " . $e->getMessage());
                }
            }

            // Log activity
            if ($logAsActivity) {
                $lead->logActivity(
                    'outreach_sent',
                    "{$validated['contact_method']} outreach sent - Type: {$validated['message_type']}"
                );
            }

            // Update interaction tracking
            $lead->increment('interaction_count');
            $lead->update(['last_interaction_at' => now()]);

            return redirect()->back()->with('success', "Monthly outreach message sent via {$contactMethod}!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error sending message: ' . $e->getMessage());
        }
    }
}
