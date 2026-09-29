<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Invitation - {{ $guest->full_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { text-align: center; border-bottom: 3px solid #1a5490; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #1a5490; font-size: 28px; margin: 10px 0; }
        .header p { color: #666; font-size: 14px; margin: 5px 0; }
        .personalization { background: #f0f4f8; border-left: 4px solid #1a5490; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .personalization h2 { color: #1a5490; font-size: 18px; margin-top: 0; }
        .field { margin: 10px 0; }
        .field-label { font-weight: bold; color: #333; font-size: 12px; text-transform: uppercase; }
        .field-value { font-size: 20px; color: #1a5490; font-weight: bold; margin-top: 5px; }
        .details { margin: 30px 0; }
        .details h3 { color: #1a5490; font-size: 16px; margin-bottom: 10px; }
        .details p { margin: 8px 0; color: #333; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd; text-align: center; color: #666; font-size: 12px; }
        .badge { display: inline-block; background: #1a5490; color: white; padding: 8px 16px; border-radius: 4px; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ilorin Children's Qur'an Recitation Championship 2026</h1>
            <p>Official E-Invitation</p>
        </div>

        <div class="personalization">
            <h2>Invitation For</h2>
            <div class="field">
                <div class="field-label">Guest Name</div>
                <div class="field-value">{{ $guest->full_name }}</div>
            </div>
            <div class="field">
                <div class="field-label">Registration Number</div>
                <div class="field-value">{{ $guest->registration_number }}</div>
                <span class="badge">Confirmed</span>
            </div>
        </div>

        <div class="details">
            <h3>Event Details</h3>
            <p><strong>Date:</strong> Thursday, 20 August 2026</p>
            <p><strong>Time:</strong> 10:00 a.m. Prompt</p>
            <p><strong>Venue:</strong> Ilorin Banquet Hall, Ahmadu Bello Way, Opposite Government House Ilorin, Kwara State</p>
        </div>

        <div class="details">
            <h3>Important Notice</h3>
            <p>Admission is strictly by invitation. Please present this invitation at the venue.</p>
        </div>

        <div class="footer">
            <p>Warm regards,</p>
            <p><strong>IEYDA & YAYEF</strong></p>
            <p>Contact: +234 907 161 5957, 07036739943 | info@yayef.org</p>
        </div>
    </div>
</body>
</html>