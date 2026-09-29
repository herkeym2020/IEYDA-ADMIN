<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GuestRegistration;
use App\Models\Setting;
use App\Mail\GenericMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi;

class GuestRegistrationController extends Controller
{
    private function guestRegistrationSettings(): array
    {
        return [
            'limit' => (int) Setting::get('guest_registration_limit', '100'),
            'device_restriction_enabled' => filter_var(Setting::get('guest_registration_device_restriction_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $settings = $this->guestRegistrationSettings();
        $totalRegistrations = GuestRegistration::count();
        if ($settings['limit'] > 0 && $totalRegistrations >= $settings['limit']) {
            return response()->json([
                'message' => 'Registration is now closed. Maximum capacity of ' . $settings['limit'] . ' guests has been reached.',
                'errors' => ['limit' => ['Registration closed']],
            ], 403);
        }

        $deviceHash = md5($request->ip() . '|' . $request->header('User-Agent'));
        if ($settings['device_restriction_enabled']) {
            $deviceDuplicate = GuestRegistration::where('device_hash', $deviceHash)->first();
            if ($deviceDuplicate) {
                return response()->json([
                    'message' => 'This device has already registered. Only one registration per device is allowed.',
                    'errors' => ['device' => ['Duplicate device']],
                ], 409);
            }
        }

        // Duplicate detection
        $duplicate = GuestRegistration::where('full_name', $request->fullName)
            ->where('email', $request->email)
            ->first();

        if ($duplicate) {
            return response()->json([
                'message' => 'A registration with this email already exists',
                'errors' => ['duplicate' => ['Duplicate registration detected']],
            ], 409);
        }

        // Generate registration number
        $registrationNumber = GuestRegistration::generateRegistrationNumber();

        // Create guest registration
        $guest = GuestRegistration::create([
            'registration_number' => $registrationNumber,
            'full_name' => $request->fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'confirmed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'device_hash' => $deviceHash,
        ]);

        // Send e-invitation email with combined PDF attached
        $emailSent = false;
        if ($guest->email) {
            try {
                $body = "Dear {$guest->full_name},\n\n"
                    . "Thank you for registering for the Ilorin Children's Qur'an Recitation Championship 2026.\n\n"
                    . "Your Registration Number: {$guest->registration_number}\n\n"
                    . "Your personalized e-invitation is attached to this email.\n\n"
                    . "Date: Thursday, 20 August 2026\n"
                    . "Time: 10:00 a.m. Prompt\n"
                    . "Venue: Ilorin Banquet Hall, Ahmadu Bello Way, Opposite Government House Ilorin, Kwara State\n\n"
                    . "Admission is strictly by invitation. Please present your invitation at the venue.\n\n"
                    . "Warm regards,\n"
                    . "IEYDA & YAYEF";

                $mail = new GenericMail(
                    'Your E-Invitation - Ilorin Children\'s Qur\'an Recitation Championship 2026',
                    $body,
                    $guest->full_name,
                    $guest->registration_number
                );

                // Generate image-based PDF with guest details overlaid on invitation images
                try {
                    $pdf = Pdf::loadView('pdf.guest-invitation-image', ['guest' => $guest]);
                    $pdfContent = $pdf->output();
                    $mail->attachData($pdfContent, 'E-Invitation-' . $guest->registration_number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                } catch (\Throwable $pdfError) {
                    \Log::warning('Image PDF generation failed', ['error' => $pdfError->getMessage()]);
                    $mail->attachData(
                        Pdf::loadView('pdf.guest-invitation-personalized', ['guest' => $guest])->output(),
                        'E-Invitation-' . $guest->registration_number . '.pdf',
                        ['mime' => 'application/pdf']
                    );
                }

                Mail::to($guest->email)->send($mail);
                $emailSent = true;
            } catch (\Throwable $e) {
                \Log::warning('Guest invitation email failed', [
                    'error' => $e->getMessage(),
                    'guest_id' => $guest->id,
                ]);
            }
        }

        return response()->json([
            'message' => 'Guest registered successfully. Your e-invitation has been sent to your email.',
            'data' => [
                'registration_number' => $guest->registration_number,
                'full_name' => $guest->full_name,
                'status' => $guest->status,
                'email_sent' => $emailSent,
            ],
        ], 201);
    }
}