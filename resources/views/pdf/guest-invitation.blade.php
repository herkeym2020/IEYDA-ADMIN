<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Invitation - {{ $registrationNumber }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #ffffff;
            color: #1a1a1a;
        }
        .invitation {
            width: 100%;
            max-width: 794px;
            margin: 0 auto;
            padding: 40px;
            border: 4px solid #2E7D32;
            border-radius: 12px;
            position: relative;
            background: linear-gradient(135deg, #f8fdf8 0%, #e8f5e9 50%, #f1f8e9 100%);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .bismillah {
            font-size: 22px;
            color: #2E7D32;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .title {
            font-size: 26px;
            font-weight: bold;
            color: #1B5E20;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .subtitle {
            font-size: 14px;
            color: #4CAF50;
            margin-top: 5px;
            font-style: italic;
        }
        .theme {
            font-size: 12px;
            color: #666;
            margin-top: 8px;
        }
        .guest-name {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #ffffff;
            border: 2px dashed #2E7D32;
            border-radius: 8px;
        }
        .guest-name .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .guest-name .name {
            font-size: 28px;
            font-weight: bold;
            color: #1B5E20;
            margin-top: 8px;
        }
        .reg-number {
            text-align: center;
            margin: 20px 0;
            padding: 12px;
            background: #2E7D32;
            color: #ffffff;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .details {
            margin: 20px 0;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .details h3 {
            color: #2E7D32;
            font-size: 16px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .detail-label {
            width: 120px;
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            flex: 1;
            color: #333;
        }
        .organizers {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 8px;
        }
        .organizers .org-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        .organizers .org-name {
            font-size: 14px;
            font-weight: bold;
            color: #1B5E20;
            line-height: 1.6;
        }
        .quote {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            font-style: italic;
            color: #555;
            font-size: 13px;
            border-left: 4px solid #2E7D32;
            background: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            font-size: 11px;
            color: #888;
        }
        .footer .contact {
            margin-top: 5px;
            color: #555;
        }
        .admission {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #fff3e0;
            border: 1px solid #ffb74d;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            color: #e65100;
        }
    </style>
</head>
<body>
    <div class="invitation">
        <div class="header">
            <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
            <div class="title">Ilorin Children's Qur'an Recitation Championship 2026</div>
            <div class="subtitle">Celebrating the Timeless Voices of Ilorin</div>
            <div class="theme">Preserving Tajwīd • Celebrating Heritage • Inspiring Future Generations</div>
        </div>

        <div class="guest-name">
            <div class="label">Cordially Invites</div>
            <div class="name">{{ $name }}</div>
        </div>

        <div class="reg-number">Registration No: {{ $registrationNumber }}</div>

        <div class="admission">ADMISSION STRICTLY BY INVITATION</div>

        <div class="details">
            <h3>Event Details</h3>
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div class="detail-value">Thursday, 20 August 2026</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Time:</div>
                <div class="detail-value">10:00 a.m. Prompt</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Venue:</div>
                <div class="detail-value">Ilorin Banquet Hall, Ahmadu Bello Way, Opposite Government House Ilorin, Kwara State</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Edition:</div>
                <div class="detail-value">Maiden Edition</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Dress Code:</div>
                <div class="detail-value">Traditional • Native • Corporate</div>
            </div>
        </div>

        <div class="organizers">
            <div class="org-label">In Collaboration With</div>
            <div class="org-name">
                ILORIN EMIRATE YOUTH DEVELOPMENT ASSOCIATION (IEYDA)<br>
                YAHAYA ALAPANSPA YOUTH EDUCATION FOUNDATION (YAYEF)
            </div>
        </div>

        <div class="quote">
            "The best among you are those who learn the Qur'an and teach it." — Sahih al-Bukhārī
        </div>

        <div class="footer">
            <div>RSVP: Yayef Secretariat</div>
            <div class="contact">☎ +234 907 161 5957, 07036739943 | ✉ info@yayef.org</div>
        </div>
    </div>
</body>
</html>