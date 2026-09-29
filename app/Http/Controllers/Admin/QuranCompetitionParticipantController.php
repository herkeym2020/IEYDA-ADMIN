<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuranCompetitionParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QuranCompetitionParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = QuranCompetitionParticipant::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('guardian_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('madrasah', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('lga')) {
            $query->where('lga', $request->lga);
        }

        $participants = $query->latest()->paginate(20)->appends($request->query());

        // Stats for summary cards
        $stats = [
            'total' => QuranCompetitionParticipant::count(),
            'pending' => QuranCompetitionParticipant::where('status', 'pending')->count(),
            'approved' => QuranCompetitionParticipant::where('status', 'approved')->count(),
            'shortlisted' => QuranCompetitionParticipant::where('status', 'shortlisted')->count(),
            'finalists' => QuranCompetitionParticipant::where('status', 'finalist')->count(),
            'winners' => QuranCompetitionParticipant::where('status', 'winner')->count(),
        ];

        return view('admin.quran-competition.index', compact('participants', 'stats'));
    }

    public function show($id)
    {
        $participant = QuranCompetitionParticipant::findOrFail($id);
        return view('admin.quran-competition.show', compact('participant'));
    }

    /**
     * Return participant data as JSON for modal display.
     */
    public function getParticipant($id)
    {
        $p = QuranCompetitionParticipant::findOrFail($id);

        $statusColors = ['pending'=>'warning','under_review'=>'info','verified'=>'info','approved'=>'success','shortlisted'=>'primary','finalist'=>'purple','winner'=>'danger','rejected'=>'dark'];

        return response()->json([
            'id' => $p->id,
            'registration_number' => $p->registration_number,
            'full_name' => $p->full_name,
            'gender' => ucfirst($p->gender),
            'date_of_birth' => $p->date_of_birth->format('F d, Y'),
            'age' => $p->age,
            'school' => $p->school,
            'lga' => $p->lga,
            'state' => $p->state,
            'address' => $p->address,
            'guardian_name' => $p->guardian_name,
            'relationship' => ucfirst($p->relationship),
            'phone' => $p->phone,
            'email' => $p->email ?: '—',
            'emergency_name' => $p->emergency_name,
            'emergency_phone' => $p->emergency_phone,
            'madrasah' => $p->madrasah,
            'teacher_name' => $p->teacher_name,
            'teacher_phone' => $p->teacher_phone ?: '—',
            'category' => ucfirst(str_replace('-',' ',$p->category)),
            'status' => ucfirst(str_replace('_',' ',$p->status)),
            'status_raw' => $p->status,
            'status_color' => $statusColors[$p->status] ?? 'secondary',
            'photo_url' => $p->photo_path ? asset('storage/' . $p->photo_path) : null,
            'decl_age' => (bool)$p->decl_age,
            'decl_accurate' => (bool)$p->decl_accurate,
            'decl_consent' => (bool)$p->decl_consent,
            'decl_rules' => (bool)$p->decl_rules,
            'admin_notes' => $p->admin_notes ?: '—',
            'created_at' => $p->created_at->format('F d, Y \a\t h:i A'),
            'profile_url' => route('admin.quran-competition.show', $p->id),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,verified,approved,shortlisted,finalist,winner,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $participant = QuranCompetitionParticipant::findOrFail($id);
        $participant->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Participant status updated successfully.');
    }

    public function export()
    {
        $participants = QuranCompetitionParticipant::all();
        $filename = 'quran-competition-participants-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Reg Number', 'Name', 'Gender', 'Age', 'DOB', 'School', 'LGA', 'State',
                'Guardian', 'Relationship', 'Phone', 'Email', 'Emergency Name', 'Emergency Phone',
                'Madrasah', 'Teacher', 'Teacher Phone', 'Category', 'Status', 'Registered At'
            ]);

            foreach ($participants as $p) {
                fputcsv($file, [
                    $p->registration_number,
                    $p->full_name,
                    $p->gender,
                    $p->age,
                    $p->date_of_birth->format('Y-m-d'),
                    $p->school,
                    $p->lga,
                    $p->state,
                    $p->guardian_name,
                    $p->relationship,
                    $p->phone,
                    $p->email,
                    $p->emergency_name,
                    $p->emergency_phone,
                    $p->madrasah,
                    $p->teacher_name,
                    $p->teacher_phone,
                    $p->category,
                    $p->status,
                    $p->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $participant = QuranCompetitionParticipant::findOrFail($id);

        // Delete photo if exists
        if ($participant->photo_path) {
            \Storage::disk('public')->delete($participant->photo_path);
        }

        $participant->delete();

        return redirect()->route('admin.quran-competition.index')
            ->with('success', 'Participant deleted successfully.');
    }

    /**
     * Send an email to the participant's guardian.
     */
    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $participant = QuranCompetitionParticipant::findOrFail($id);

        // Try to send email if mail is configured
        $mailer = config('mail.default');
        $smtpHost = config('mail.mailers.smtp.host');
        $canSend = $mailer && ($mailer !== 'smtp' || !empty($smtpHost));

        if (!$canSend) {
            return redirect()->back()->with('warning', 'Email is not configured. Please set up SMTP in .env file. Your message was NOT sent.');
        }

        // Use participant email if available
        $recipientEmail = $participant->email;
        if (!$recipientEmail) {
            return redirect()->back()->with('error', 'This participant has no email address on file. You can reach them via phone: ' . $participant->phone);
        }

        try {
            $emailBody = "Assalamu Alaikum,\n\n"
                . "This message is from the IEYDA Qur'an Competition organizing team regarding the registration of {$participant->full_name} (Reg. No: {$participant->registration_number}).\n\n"
                . $request->message
                . "\n\nJazakumullah Khairan,\nIEYDA & YAYEF Team";

            Mail::raw($emailBody, function ($mail) use ($recipientEmail, $request) {
                $mail->to($recipientEmail)
                    ->subject($request->subject);
            });

            return redirect()->back()->with('success', 'Email sent successfully to ' . $recipientEmail);
        } catch (\Throwable $e) {
            \Log::error('Failed to send participant email', [
                'error' => $e->getMessage(),
                'participant_id' => $participant->id,
            ]);
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}