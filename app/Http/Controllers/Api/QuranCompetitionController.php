<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuranCompetitionParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QuranCompetitionController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'dateOfBirth' => 'required|date|before_or_equal:today',
            'school' => 'required|string|max:255',
            'lga' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'address' => 'required|string',
            'guardianName' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'emergencyName' => 'required|string|max:255',
            'emergencyPhone' => 'required|string|max:30',
            'madrasah' => 'required|string|max:255',
            'teacherName' => 'required|string|max:255',
            'teacherPhone' => 'nullable|string|max:30',
            'category' => 'required|array|min:1',
            'category.*' => 'required|in:markaz,adaby,zumurah,imam-agba,asily',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'declAge' => 'required|boolean|accepted',
            'declAccurate' => 'required|boolean|accepted',
            'declConsent' => 'required|boolean|accepted',
            'declRules' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Calculate age
        $dob = Carbon::parse($request->dateOfBirth);
        $age = $dob->age;

        if ($age > 16) {
            return response()->json([
                'message' => 'Participant must be 16 years or younger',
                'errors' => ['dateOfBirth' => ['Participant exceeds the age limit of 16 years']],
            ], 422);
        }

        // Duplicate detection (same name + same guardian phone)
        $duplicate = QuranCompetitionParticipant::where('full_name', $request->fullName)
            ->where('phone', $request->phone)
            ->first();

        if ($duplicate) {
            return response()->json([
                'message' => 'A registration with this name and guardian phone already exists',
                'errors' => ['duplicate' => ['Duplicate registration detected']],
            ], 409);
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('quran-competition/photos', 'public');
        }

        // Generate registration number
        $registrationNumber = QuranCompetitionParticipant::generateRegistrationNumber();

        // Create participant
        $participant = QuranCompetitionParticipant::create([
            'registration_number' => $registrationNumber,
            'full_name' => $request->fullName,
            'gender' => $request->gender,
            'date_of_birth' => $request->dateOfBirth,
            'age' => $age,
            'school' => $request->school,
            'lga' => $request->lga,
            'state' => $request->state,
            'address' => $request->address,
            'guardian_name' => $request->guardianName,
            'relationship' => $request->relationship,
            'phone' => $request->phone,
            'email' => $request->email,
            'emergency_name' => $request->emergencyName,
            'emergency_phone' => $request->emergencyPhone,
            'madrasah' => $request->madrasah,
            'teacher_name' => $request->teacherName,
            'teacher_phone' => $request->teacherPhone,
            'category' => $request->category,
            'photo_path' => $photoPath,
            'decl_age' => $request->boolean('declAge'),
            'decl_accurate' => $request->boolean('declAccurate'),
            'decl_consent' => $request->boolean('declConsent'),
            'decl_rules' => $request->boolean('declRules'),
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        // Send confirmation email to registrant (best-effort)
        try {
            $mailer = config('mail.default');
            $smtpHost = config('mail.mailers.smtp.host');
            $canSend = $mailer && ($mailer !== 'smtp' || !empty($smtpHost));

            if ($canSend && $request->email) {
                $emailBody = "Assalamu Alaikum,\n\n"
                    . "Your registration for the Ilorin Children's Qur'an Recitation Competition has been received successfully.\n\n"
                    . "Registration Number: {$registrationNumber}\n"
                    . "Participant Name: {$request->fullName}\n"
                    . "Category: " . ucfirst(str_replace('-', ' ', $request->category)) . "\n"
                    . "Age: {$age} years\n\n"
                    . "Event Details:\n"
                    . "Date: 5th August\n"
                    . "Venue: Kwara State Banquet Hall, Ilorin\n"
                    . "Time: 9:00 AM (Registration starts 8:00 AM)\n\n"
                    . "Please save your registration number for future reference.\n\n"
                    . "Jazakumullah Khairan,\n"
                    . "IEYDA & YAYEF";

                Mail::raw($emailBody, function ($mail) use ($request, $registrationNumber) {
                    $mail->to($request->email)
                        ->subject('Registration Confirmed - Ilorin Qur\'an Recitation Competition [' . $registrationNumber . ']');
                });
            }

            // Also notify admin
            $adminEmail = config('mail.from.address') ?: env('MAIL_TO');
            if ($canSend && $adminEmail) {
                $adminBody = "New registration received for the Ilorin Children's Qur'an Recitation Competition.\n\n"
                    . "Registration Number: {$registrationNumber}\n"
                    . "Name: {$request->fullName}\n"
                    . "Category: " . ucfirst(str_replace('-', ' ', $request->category)) . "\n"
                    . "Age: {$age}\n"
                    . "Guardian: {$request->guardianName} ({$request->phone})\n"
                    . "LGA: {$request->lga}, State: {$request->state}\n\n"
                    . "Please review in the admin panel.";

                Mail::raw($adminBody, function ($mail) use ($adminEmail, $registrationNumber) {
                    $mail->to($adminEmail)
                        ->subject('New Qur\'an Competition Registration [' . $registrationNumber . ']');
                });
            }
        } catch (\Throwable $e) {
            \Log::warning('Quran competition notification email failed', [
                'error' => $e->getMessage(),
                'participant_id' => $participant->id,
            ]);
        }

        return response()->json([
            'message' => 'Registration submitted successfully',
            'data' => [
                'registration_number' => $registrationNumber,
                'full_name' => $participant->full_name,
                'category' => $participant->category,
                'status' => $participant->status,
            ],
        ], 201);
    }

    public function stats()
    {
        $total = QuranCompetitionParticipant::count();
        $pending = QuranCompetitionParticipant::where('status', 'pending')->count();
        $approved = QuranCompetitionParticipant::where('status', 'approved')->count();
        $shortlisted = QuranCompetitionParticipant::where('status', 'shortlisted')->count();
        $finalists = QuranCompetitionParticipant::where('status', 'finalist')->count();
        $winners = QuranCompetitionParticipant::where('status', 'winner')->count();

        $categoryDistribution = QuranCompetitionParticipant::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $genderDistribution = QuranCompetitionParticipant::selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        return response()->json([
            'message' => 'Stats retrieved successfully',
            'data' => [
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'shortlisted' => $shortlisted,
                'finalists' => $finalists,
                'winners' => $winners,
                'category_distribution' => $categoryDistribution,
                'gender_distribution' => $genderDistribution,
            ],
        ]);
    }
}