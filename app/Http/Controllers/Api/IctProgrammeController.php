<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IctProgrammeApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IctProgrammeController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'dateOfBirth' => 'required|date|before_or_equal:today',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'lga' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'educationLevel' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'ictCourses' => 'required|array|min:1|max:1',
            'ictCourses.*' => 'required|string|in:digital_marketing,document_management,digital_presentation,graphics_design,virtual_classroom,general_ai,cctv_installation',
            'vocationalInterests' => 'nullable|array',
            'vocationalInterests.*' => 'nullable|string|in:custard_production,air_freshener,perfume_balm,liquid_soap,scouring_powder',
            'expectations' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'guardianName' => 'nullable|string|max:255',
            'guardianPhone' => 'nullable|string|max:30',
            'guardianRelationship' => 'nullable|string|max:100',
            'declAccurate' => 'required|in:true,false,1,0,on,off,yes,no',
            'declConsent' => 'required|in:true,false,1,0,on,off,yes,no',
            'declRules' => 'required|in:true,false,1,0,on,off,yes,no',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate declarations are all true
        $declarations = ['declAccurate', 'declConsent', 'declRules'];
        foreach ($declarations as $decl) {
            $value = $request->input($decl);
            $isTrue = in_array($value, ['true', '1', 'on', 'yes', 1, true], true);
            if (!$isTrue) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [$decl => ['You must agree to all declarations']],
                ], 422);
            }
        }

        // Calculate age
        $dob = Carbon::parse($request->dateOfBirth);
        $age = $dob->age;

        // Duplicate detection (same name + same phone)
        $duplicate = IctProgrammeApplicant::where('full_name', $request->fullName)
            ->where('phone', $request->phone)
            ->first();

        if ($duplicate) {
            return response()->json([
                'message' => 'A registration with this name and phone already exists',
                'errors' => ['duplicate' => ['Duplicate registration detected']],
            ], 409);
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ict-programme/photos', 'public');
        }

        // Generate registration number
        $registrationNumber = IctProgrammeApplicant::generateRegistrationNumber();

        // Create applicant
        $applicant = IctProgrammeApplicant::create([
            'registration_number' => $registrationNumber,
            'full_name' => $request->fullName,
            'gender' => $request->gender,
            'date_of_birth' => $request->dateOfBirth,
            'age' => $age,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'lga' => $request->lga,
            'state' => $request->state ?? 'Kwara',
            'education_level' => $request->educationLevel,
            'occupation' => $request->occupation,
            'ict_courses' => $request->ictCourses,
            'vocational_interests' => $request->vocationalInterests ?? [],
            'expectations' => $request->expectations,
            'photo_path' => $photoPath,
            'guardian_name' => $request->guardianName,
            'guardian_phone' => $request->guardianPhone,
            'guardian_relationship' => $request->guardianRelationship,
            'decl_accurate' => in_array($request->input('declAccurate'), ['true', '1', 'on', 'yes', 1, true], true),
            'decl_consent' => in_array($request->input('declConsent'), ['true', '1', 'on', 'yes', 1, true], true),
            'decl_rules' => in_array($request->input('declRules'), ['true', '1', 'on', 'yes', 1, true], true),
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notifications asynchronously to avoid blocking the response
        $applicantId = $applicant->id;
        dispatch(function () use ($applicantId) {
            $applicant = IctProgrammeApplicant::find($applicantId);
            if (!$applicant) return;

            $controller = new self();
            $controller->sendAcknowledgmentEmail($applicant);
            $controller->sendWhatsAppMessage($applicant);
        })->afterResponse();

        return response()->json([
            'message' => 'Registration submitted successfully',
            'data' => [
                'registration_number' => $applicant->registration_number,
                'full_name' => $applicant->full_name,
                'status' => $applicant->status,
                'ict_courses' => $applicant->ict_courses,
            ],
        ], 201);
    }

    /**
     * Send acknowledgment email to applicant
     */
    private function sendAcknowledgmentEmail($applicant)
    {
        if (!$applicant->email) return;

        $courses = $applicant->formatted_ict_courses;
        $vocational = $applicant->formatted_vocational_interests;

        $emailBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #2E7D32, #1B5E20); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                <h1 style='margin: 0; font-size: 24px;'>IEYDA ICT Programme</h1>
                <p style='margin: 5px 0 0; opacity: 0.9;'>11th Free ICT & Vocational Skills Acquisition Programme</p>
            </div>
            <div style='padding: 30px; background: #f9f9f9; border-radius: 0 0 10px 10px;'>
                <h2 style='color: #2E7D32; margin-top: 0;'>Dear {$applicant->full_name},</h2>
                <p style='font-size: 16px; line-height: 1.6; color: #333;'>
                    Your registration for the IEYDA 11th Free ICT and Vocational Skills Acquisition Programme has been received successfully.
                </p>
                <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32;'>
                    <p style='margin: 0 0 10px;'><strong>Registration Number:</strong> {$applicant->registration_number}</p>
                    <p style='margin: 0 0 10px;'><strong>Selected ICT Courses:</strong> {$courses}</p>
                    " . ($vocational ? "<p style='margin: 0 0 10px;'><strong>Vocational Sessions:</strong> {$vocational}</p>" : "") . "
                    <p style='margin: 0;'><strong>Status:</strong> Pending Review</p>
                </div>
                <div style='background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='margin-top: 0; color: #2E7D32; font-size: 16px;'>📅 Programme Details</h3>
                    <p style='margin: 5px 0; font-size: 14px;'><strong>Training Commences:</strong> Monday, August 3, 2026</p>
                    <p style='margin: 5px 0; font-size: 14px;'><strong>Venue:</strong> IEYDA National Secretariat, Aishat Adepate House, 46 Edun Street, Ilorin</p>
                    <p style='margin: 5px 0; font-size: 14px;'><strong>Cost:</strong> FREE (No charges)</p>
                </div>
                <div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; font-size: 14px; color: #856404;'>
                        <strong>📌 Important:</strong> Please save your registration number. You will need it for admission verification.
                    </p>
                </div>
                <p style='font-size: 14px; color: #666; margin-top: 20px;'>
                    For enquiries, contact: 07036739943, 08151515608, 08033700725, or 08065232374
                </p>
                <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                <p style='font-size: 12px; color: #999; text-align: center;'>
                    Ilorin Emirate Youth Development Association (IEYDA)<br>
                    Under the auspices of the Emir of Ilorin, HRH Alhaji (Dr.) Ibrahim Sulu-Gambari, CFR
                </p>
            </div>
        </div>";

        try {
            Mail::html($emailBody, function ($message) use ($applicant) {
                $message->to($applicant->email, $applicant->full_name)
                    ->subject('IEYDA ICT Programme Registration Successful - ' . $applicant->registration_number)
                    ->from('noreply.ieyda@gmail.com', 'IEYDA');
            });

            $applicant->update(['email_sent_at' => now()]);
        } catch (\Exception $e) {
            \Log::error('ICT Programme email failed: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp acknowledgment message
     */
    private function sendWhatsAppMessage($applicant)
    {
        if (!$applicant->phone) return;

        $courses = $applicant->formatted_ict_courses;
        $phone = preg_replace('/[^0-9]/', '', $applicant->phone);
        if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
            $phone = '234' . substr($phone, 1);
        }

        $message = "🎉 *IEYDA ICT Programme Registration Successful!*\n\n";
        $message .= "Dear {$applicant->full_name},\n\n";
        $message .= "Your registration for the IEYDA 11th Free ICT & Vocational Skills Acquisition Programme has been received.\n\n";
        $message .= "📋 *Registration Number:* {$applicant->registration_number}\n";
        $message .= "📚 *Selected Course:* {$courses}\n";
        $message .= "📊 *Status:* Pending Review\n\n";
        $message .= "📅 *Training Commences:* Monday, August 3, 2026\n";
        $message .= "📍 *Venue:* IEYDA National Secretariat, Aishat Adepate House, 46 Edun Street, Ilorin\n";
        $message .= "💰 *Cost:* FREE\n\n";
        $message .= "⚠️ *Important:* Save your registration number for admission verification.\n\n";
        $message .= "📞 *Enquiries:* 07036739943, 08151515608, 08033700725, 08065232374\n\n";
        $message .= "_Ilorin Emirate Youth Development Association (IEYDA)_";

        try {
            $whatsappService = app(\App\Services\WhatsAppService::class);
            $whatsappService->sendMessage($phone, $message);
            $applicant->update(['whatsapp_sent_at' => now()]);
        } catch (\Exception $e) {
            \Log::error('ICT Programme WhatsApp failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate and send admission letter as PDF attachment
     */
    public function sendAdmissionLetter($applicant)
    {
        $courses = $applicant->formatted_ict_courses;
        $vocational = $applicant->formatted_vocational_interests;

        $admissionNumber = 'ADM-' . $applicant->registration_number;

        // Generate PDF using DomPDF
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.ict-admission-letter', [
                'applicant' => $applicant,
                'admissionNumber' => $admissionNumber,
                'courses' => $courses,
                'vocational' => $vocational,
            ])->setPaper('a4', 'portrait');

            $pdfContent = $pdf->output();
            $pdfFilename = 'Admission_Letter_' . $admissionNumber . '.pdf';
        } catch (\Exception $e) {
            \Log::error('ICT Admission PDF generation failed: ' . $e->getMessage());
            $pdfContent = null;
            $pdfFilename = null;
        }

        // Send email with PDF attachment
        if ($applicant->email) {
            try {
                $emailBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <div style='background: linear-gradient(135deg, #2E7D32, #1B5E20); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                        <h1 style='margin: 0; font-size: 24px;'>IEYDA ICT Programme</h1>
                        <p style='margin: 5px 0 0; opacity: 0.9;'>OFFICIAL ADMISSION LETTER</p>
                    </div>
                    <div style='padding: 30px; background: #f9f9f9; border-radius: 0 0 10px 10px;'>
                        <h2 style='color: #2E7D32; margin-top: 0;'>Dear {$applicant->full_name},</h2>
                        <p style='font-size: 16px; line-height: 1.6; color: #333;'>
                            Congratulations! We are pleased to inform you that you have been <strong>ADMITTED</strong> into the 
                            IEYDA 11th Free ICT & Vocational Skills Acquisition Programme.
                        </p>
                        <div style='background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32;'>
                            <p style='margin: 0 0 10px;'><strong>Admission Number:</strong> {$admissionNumber}</p>
                            <p style='margin: 0 0 10px;'><strong>Registration Number:</strong> {$applicant->registration_number}</p>
                            <p style='margin: 0 0 10px;'><strong>ICT Course:</strong> {$courses}</p>
                            " . ($vocational ? "<p style='margin: 0 0 10px;'><strong>Vocational Sessions:</strong> {$vocational}</p>" : "") . "
                            <p style='margin: 0;'><strong>Training Commences:</strong> Monday, August 3, 2026</p>
                        </div>
                        <div style='background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                            <h3 style='margin-top: 0; color: #2E7D32; font-size: 16px;'>📍 Venue</h3>
                            <p style='margin: 5px 0; font-size: 14px;'>IEYDA National Secretariat, Aishat Adepate House, 46 Edun Street, Ilorin</p>
                            <p style='margin: 5px 0; font-size: 14px;'><strong>Time:</strong> 9:00 AM - 4:00 PM Daily</p>
                        </div>
                        <div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                            <p style='margin: 0; font-size: 14px; color: #856404;'>
                                <strong>📌 Important:</strong> Please find attached your official admission letter in PDF format. 
                                Print it and bring it along on the first day.
                            </p>
                        </div>
                        <p style='font-size: 14px; color: #666; margin-top: 20px;'>
                            For enquiries, contact: 07036739943, 08151515608, 08033700725, or 08065232374
                        </p>
                        <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                        <p style='font-size: 12px; color: #999; text-align: center;'>
                            Ilorin Emirate Youth Development Association (IEYDA)<br>
                            Under the auspices of the Emir of Ilorin, HRH Alhaji (Dr.) Ibrahim Sulu-Gambari, CFR
                        </p>
                    </div>
                </div>";

                Mail::html($emailBody, function ($message) use ($applicant, $admissionNumber, $pdfContent, $pdfFilename) {
                    $message->to($applicant->email, $applicant->full_name)
                        ->subject('ADMISSION LETTER - ' . $admissionNumber)
                        ->from('noreply.ieyda@gmail.com', 'IEYDA');

                    if ($pdfContent && $pdfFilename) {
                        $message->attachData($pdfContent, $pdfFilename, ['mime' => 'application/pdf']);
                    }
                });
            } catch (\Exception $e) {
                \Log::error('ICT Admission letter email failed: ' . $e->getMessage());
            }
        }

        // Send WhatsApp with admission details
        if ($applicant->phone) {
            try {
                $phone = preg_replace('/[^0-9]/', '', $applicant->phone);
                if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
                    $phone = '234' . substr($phone, 1);
                }

                $waMessage = "🎓 *OFFICIAL ADMISSION LETTER*\n\n";
                $waMessage .= "Dear {$applicant->full_name},\n\n";
                $waMessage .= "🎉 *CONGRATULATIONS!* You have been ADMITTED into the IEYDA 11th Free ICT & Vocational Skills Acquisition Programme.\n\n";
                $waMessage .= "📋 *Admission Number:* {$admissionNumber}\n";
                $waMessage .= "📚 *ICT Course:* {$courses}\n";
                if ($vocational) $waMessage .= "🧪 *Vocational Sessions:* {$vocational}\n";
                $waMessage .= "\n📅 *Training Commences:* Monday, August 3, 2026\n";
                $waMessage .= "📍 *Venue:* IEYDA National Secretariat, Aishat Adepate House, 46 Edun Street, Ilorin\n";
                $waMessage .= "⏰ *Time:* 9:00 AM - 4:00 PM Daily\n\n";
                $waMessage .= "📌 *Requirements:* Bring your registration number, valid ID, and passport photograph.\n\n";
                $waMessage .= "📞 *Enquiries:* 07036739943, 08151515608, 08033700725, 08065232374\n\n";
                $waMessage .= "_Ilorin Emirate Youth Development Association (IEYDA)_";

                $whatsappService = app(\App\Services\WhatsAppService::class);
                $whatsappService->sendMessage($phone, $waMessage);
            } catch (\Exception $e) {
                \Log::error('ICT Admission letter WhatsApp failed: ' . $e->getMessage());
            }
        }
    }
}
