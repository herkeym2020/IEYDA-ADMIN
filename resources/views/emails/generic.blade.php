<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 650px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .header { background: #1a5490; color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 30px; color: #333; line-height: 1.6; }
        .body p { margin: 0 0 15px; }
        .body .highlight { background: #f0f4f8; border-left: 4px solid #1a5490; padding: 15px; margin: 20px 0; }
        .body .reg-number { font-size: 22px; font-weight: bold; color: #1a5490; }
        .footer { background: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #777; }
        .btn { display: inline-block; padding: 12px 24px; background: #1a5490; color: #fff; text-decoration: none; border-radius: 4px; margin: 10px 5px 0 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ilorin Children's Qur'an Recitation Championship 2026</h1>
        </div>
        <div class="body">
            <p>Dear {{ $recipientName ?? 'Guest' }},</p>
            <p>Thank you for registering for the Ilorin Children's Qur'an Recitation Championship 2026.</p>

            <div class="highlight">
                <p>Your Registration Number:</p>
                <p class="reg-number">{{ $registrationNumber }}</p>
            </div>

            <p>Your official e-invitation is attached to this email as a PDF. Please download and present it at the venue.</p>

            <p><strong>Event Details:</strong><br>
            Date: Thursday, 20 August 2026<br>
            Time: 10:00 a.m. Prompt<br>
            Venue: Ilorin Banquet Hall, Ahmadu Bello Way, Opposite Government House Ilorin, Kwara State</p>

            <p>Admission is strictly by invitation.</p>

            <p>Warm regards,<br>
            <strong>IEYDA & YAYEF</strong><br>
            Contact: +234 907 161 5957, 07036739943 | info@yayef.org</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} IEYDA / YAYEF. All rights reserved.
        </div>
    </div>
</body>
</html>