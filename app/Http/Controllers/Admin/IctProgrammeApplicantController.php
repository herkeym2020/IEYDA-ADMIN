<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IctProgrammeApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class IctProgrammeApplicantController extends Controller
{
    /**
     * Display list of applicants with filtering
     */
    public function index(Request $request)
    {
        $query = IctProgrammeApplicant::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('occupation', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by gender
        if ($gender = $request->get('gender')) {
            $query->where('gender', $gender);
        }

        // Filter by ICT course
        if ($course = $request->get('course')) {
            $query->whereJsonContains('ict_courses', $course);
        }

        $participants = $query->latest()->paginate(15)->appends($request->query());

        // Stats
        $stats = [
            'total' => IctProgrammeApplicant::count(),
            'pending' => IctProgrammeApplicant::pending()->count(),
            'approved' => IctProgrammeApplicant::approved()->count(),
            'admitted' => IctProgrammeApplicant::admitted()->count(),
            'rejected' => IctProgrammeApplicant::rejected()->count(),
            'completed' => IctProgrammeApplicant::completed()->count(),
        ];

        return view('admin.ict-programme.index', compact('participants', 'stats'));
    }

    /**
     * Display single applicant
     */
    public function show($id)
    {
        $participant = IctProgrammeApplicant::findOrFail($id);
        return view('admin.ict-programme.show', compact('participant'));
    }

    /**
     * Get applicant data as JSON (for modal)
     */
    public function getJson($id)
    {
        $p = IctProgrammeApplicant::findOrFail($id);

        return response()->json([
            'id' => $p->id,
            'registration_number' => $p->registration_number,
            'full_name' => $p->full_name,
            'gender' => ucfirst($p->gender),
            'date_of_birth' => $p->date_of_birth->format('M d, Y'),
            'age' => $p->age,
            'phone' => $p->phone,
            'email' => $p->email ?: 'N/A',
            'address' => $p->address,
            'lga' => $p->lga ?: 'N/A',
            'state' => $p->state,
            'education_level' => $p->education_level ?: 'N/A',
            'occupation' => $p->occupation ?: 'N/A',
            'ict_courses' => $p->formatted_ict_courses,
            'vocational_interests' => $p->formatted_vocational_interests ?: 'None selected',
            'expectations' => $p->expectations ?: 'N/A',
            'photo_url' => $p->photo_path ? asset('storage/' . $p->photo_path) : null,
            'guardian_name' => $p->guardian_name ?: 'N/A',
            'guardian_phone' => $p->guardian_phone ?: 'N/A',
            'guardian_relationship' => $p->guardian_relationship ?: 'N/A',
            'status' => ucfirst(str_replace('_', ' ', $p->status)),
            'status_color' => $p->status_color,
            'admin_notes' => $p->admin_notes ?: 'N/A',
            'created_at' => $p->created_at->format('M d, Y g:i A'),
            'email_sent' => $p->email_sent_at ? 'Yes (' . $p->email_sent_at->format('M d, Y g:i A') . ')' : 'No',
            'whatsapp_sent' => $p->whatsapp_sent_at ? 'Yes (' . $p->whatsapp_sent_at->format('M d, Y g:i A') . ')' : 'No',
            'profile_url' => route('admin.ict-programme.show', $p->id),
        ]);
    }

    /**
     * Update applicant status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,admitted,rejected,completed,graduated',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $participant = IctProgrammeApplicant::findOrFail($id);
        $oldStatus = $participant->status;
        $newStatus = $request->status;

        $participant->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ]);

        // Set admitted_at timestamp
        if ($newStatus === 'admitted' && !$participant->admitted_at) {
            $participant->update(['admitted_at' => now()]);
        }

        // Send status update notification
        if ($oldStatus !== $newStatus) {
            $this->sendStatusUpdateNotification($participant, $oldStatus, $newStatus);
        }

        // Send admission letter when admitted
        if ($newStatus === 'admitted' && $oldStatus !== 'admitted') {
            $apiController = app(\App\Http\Controllers\Api\IctProgrammeController::class);
            $apiController->sendAdmissionLetter($participant);
        }

        return redirect()->back()->with('success', "Status updated to '{$newStatus}' successfully.");
    }

    /**
     * Bulk update status for multiple applicants
     */
    public function bulkUpdateStatus(Request $request)
    {
        // Handle JSON-encoded IDs from form
        $ids = $request->ids;
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        $request->merge(['ids' => $ids]);

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:ict_programme_applicants,id',
            'status' => 'required|in:pending,approved,admitted,rejected,completed,graduated',
        ]);
        $newStatus = $request->status;
        $count = 0;

        foreach ($ids as $id) {
            $participant = IctProgrammeApplicant::find($id);
            if (!$participant) continue;

            $oldStatus = $participant->status;
            $participant->update(['status' => $newStatus]);

            if ($newStatus === 'admitted' && !$participant->admitted_at) {
                $participant->update(['admitted_at' => now()]);
            }

            if ($oldStatus !== $newStatus) {
                $this->sendStatusUpdateNotification($participant, $oldStatus, $newStatus);
            }

            // Send admission letter when admitted
            if ($newStatus === 'admitted' && $oldStatus !== 'admitted') {
                $apiController = app(\App\Http\Controllers\Api\IctProgrammeController::class);
                $apiController->sendAdmissionLetter($participant);
            }

            $count++;
        }

        return redirect()->back()->with('success', "Updated status to '{$newStatus}' for {$count} applicant(s).");
    }

    /**
     * Bulk send custom message to applicants
     */
    public function bulkSendMessage(Request $request)
    {
        // Handle JSON-encoded IDs from form
        $ids = $request->ids;
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        $request->merge(['ids' => $ids]);

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:ict_programme_applicants,id',
            'message' => 'required|string|max:1000',
        ]);
        $message = $request->message;
        $count = 0;

        foreach ($ids as $id) {
            $participant = IctProgrammeApplicant::find($id);
            if (!$participant) continue;

            // Send email
            if ($participant->email) {
                try {
                    $emailBody = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <div style='background: #2E7D32; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                            <h2 style='margin: 0;'>IEYDA ICT Programme</h2>
                        </div>
                        <div style='padding: 30px; background: #f9f9f9; border-radius: 0 0 10px 10px;'>
                            <p>Dear {$participant->full_name},</p>
                            <p>" . nl2br(e($message)) . "</p>
                            <p style='font-size: 14px; color: #666;'>For enquiries: 07036739943, 08151515608, 08033700725, 08065232374</p>
                        </div>
                    </div>";

                    Mail::html($emailBody, function ($m) use ($participant) {
                        $m->to($participant->email, $participant->full_name)
                          ->subject('IEYDA ICT Programme - Message')
                          ->from('noreply.ieyda@gmail.com', 'IEYDA');
                    });
                } catch (\Exception $e) {
                    \Log::error('ICT bulk email failed: ' . $e->getMessage());
                }
            }

            // Send WhatsApp
            if ($participant->phone) {
                try {
                    $phone = preg_replace('/[^0-9]/', '', $participant->phone);
                    if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
                        $phone = '234' . substr($phone, 1);
                    }

                    $waMessage = "📢 *IEYDA ICT Programme*\n\n";
                    $waMessage .= "Dear {$participant->full_name},\n\n";
                    $waMessage .= "{$message}\n\n";
                    $waMessage .= "_IEYDA_";

                    $whatsappService = app(\App\Services\WhatsAppService::class);
                    $whatsappService->sendMessage($phone, $waMessage);
                } catch (\Exception $e) {
                    \Log::error('ICT bulk WhatsApp failed: ' . $e->getMessage());
                }
            }

            $count++;
        }

        return redirect()->back()->with('success', "Message sent to {$count} applicant(s).");
    }

    /**
     * Bulk verify applicants (mark as verified)
     */
    public function bulkVerify(Request $request)
    {
        // Handle JSON-encoded IDs from form
        $ids = $request->ids;
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        $request->merge(['ids' => $ids]);

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:ict_programme_applicants,id',
        ]);
        $count = 0;

        foreach ($ids as $id) {
            $participant = IctProgrammeApplicant::find($id);
            if (!$participant) continue;

            if ($participant->status === 'pending') {
                $participant->update(['status' => 'approved']);
                $this->sendStatusUpdateNotification($participant, 'pending', 'approved');
                $count++;
            }
        }

        return redirect()->back()->with('success', "Verified {$count} applicant(s).");
    }

    /**
     * Export applicants to CSV
     */
    public function export()
    {
        $participants = IctProgrammeApplicant::latest()->get();

        $filename = 'ict-programme-applicants-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Reg Number', 'Name', 'Gender', 'Age', 'Phone', 'Email', 'Address',
                'LGA', 'State', 'Education Level', 'Occupation', 'ICT Courses',
                'Vocational Interests', 'Guardian Name', 'Guardian Phone', 'Status',
                'Email Sent', 'WhatsApp Sent', 'Registered At'
            ]);

            foreach ($participants as $p) {
                fputcsv($file, [
                    $p->registration_number,
                    $p->full_name,
                    $p->gender,
                    $p->age,
                    $p->phone,
                    $p->email ?: '',
                    $p->address,
                    $p->lga ?: '',
                    $p->state,
                    $p->education_level ?: '',
                    $p->occupation ?: '',
                    $p->formatted_ict_courses,
                    $p->formatted_vocational_interests,
                    $p->guardian_name ?: '',
                    $p->guardian_phone ?: '',
                    $p->status,
                    $p->email_sent_at ? 'Yes' : 'No',
                    $p->whatsapp_sent_at ? 'Yes' : 'No',
                    $p->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Send status update email and WhatsApp
     */
    private function sendStatusUpdateNotification($applicant, $oldStatus, $newStatus)
    {
        $statusMessages = [
            'approved' => 'Your application has been approved! Please check your email for further instructions.',
            'admitted' => 'Congratulations! You have been admitted to the IEYDA ICT Programme. Please report to the venue on August 3, 2026.',
            'rejected' => 'We regret to inform you that your application was not successful at this time.',
            'completed' => 'You have successfully completed the IEYDA ICT Programme. Congratulations!',
            'graduated' => 'Congratulations on graduating from the IEYDA ICT Programme!',
        ];

        $message = $statusMessages[$newStatus] ?? "Your application status has been updated to: {$newStatus}";

        // Send email
        if ($applicant->email) {
            try {
                $emailBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <div style='background: #2E7D32; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                        <h2 style='margin: 0;'>IEYDA ICT Programme - Status Update</h2>
                    </div>
                    <div style='padding: 30px; background: #f9f9f9; border-radius: 0 0 10px 10px;'>
                        <p>Dear {$applicant->full_name},</p>
                        <p>{$message}</p>
                        <div style='background: white; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #2E7D32;'>
                            <p style='margin: 0;'><strong>Registration Number:</strong> {$applicant->registration_number}</p>
                            <p style='margin: 5px 0 0;'><strong>New Status:</strong> " . ucfirst($newStatus) . "</p>
                        </div>
                        <p style='font-size: 14px; color: #666;'>For enquiries: 07036739943, 08151515608, 08033700725, 08065232374</p>
                    </div>
                </div>";

                Mail::html($emailBody, function ($m) use ($applicant) {
                    $m->to($applicant->email, $applicant->full_name)
                      ->subject('IEYDA ICT Programme - Status Update')
                      ->from('noreply.ieyda@gmail.com', 'IEYDA');
                });
            } catch (\Exception $e) {
                \Log::error('ICT status email failed: ' . $e->getMessage());
            }
        }

        // Send WhatsApp
        if ($applicant->phone) {
            try {
                $phone = preg_replace('/[^0-9]/', '', $applicant->phone);
                if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
                    $phone = '234' . substr($phone, 1);
                }

                $waMessage = "📢 *IEYDA ICT Programme - Status Update*\n\n";
                $waMessage .= "Dear {$applicant->full_name},\n\n";
                $waMessage .= "{$message}\n\n";
                $waMessage .= "📋 *Reg Number:* {$applicant->registration_number}\n";
                $waMessage .= "📊 *Status:* " . ucfirst($newStatus) . "\n\n";
                $waMessage .= "_IEYDA_";

                $whatsappService = app(\App\Services\WhatsAppService::class);
                $whatsappService->sendMessage($phone, $waMessage);
            } catch (\Exception $e) {
                \Log::error('ICT status WhatsApp failed: ' . $e->getMessage());
            }
        }
    }
}