<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'category' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        // Store in DB
        $contact = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'category' => $request->category,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'status' => 'new',
        ]);

            // Send email (best-effort; avoid SMTP timeouts when mail isn't configured)
            try {
                $mailer = config('mail.default');
                $smtpHost = config('mail.mailers.smtp.host');
                $toAddress = config('mail.from.address') ?: env('MAIL_TO');

                // Only attempt mail if we have a destination and a usable mailer config
                $canSend = $toAddress && $mailer && ($mailer !== 'smtp' || !empty($smtpHost));

                if ($canSend) {
                    Mail::raw(
                        "Name: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\nCategory: {$request->category}\nSubject: {$request->subject}\nMessage: {$request->message}",
                        function ($mail) use ($request, $toAddress) {
                            $mail->to($toAddress)
                                ->subject('Contact Form: ' . $request->subject);
                        }
                    );
                }
            } catch (\Throwable $e) {
                // Log but return success so frontend doesn't error
                \Log::warning('Contact email failed', [
                    'error' => $e->getMessage(),
                ]);
            }

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $contact
        ]);
    }
}
