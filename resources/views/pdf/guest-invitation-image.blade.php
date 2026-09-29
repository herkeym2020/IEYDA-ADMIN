<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Invitation - {{ $guest->full_name }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 portrait;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .page {
            width: 210mm;
            height: 297mm;
            position: relative;
            page-break-after: always;
            overflow: hidden;
        }
        .page:last-child {
            page-break-after: auto;
        }
        .page img.invitation-image {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
        }
        .guest-info {
            position: absolute;
            top: 39%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 84%;
            z-index: 10;
        }
        .guest-name {
            font-size: 30px;
            font-weight: 700;
            color: #103f6b;
            margin-bottom: 16px;
            padding: 12px 22px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            display: inline-block;
            min-width: 320px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.18);
        }
        .reg-number {
            font-size: 24px;
            font-weight: 700;
            color: #8b6a1f;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            display: inline-block;
            border: 2px solid #8b6a1f;
            box-shadow: 0 3px 10px rgba(0,0,0,0.16);
        }
    </style>
</head>
<body>
    <!-- Cover Page with Guest Details -->
    <div class="page">
        <img class="invitation-image" src="{{ public_path('QuranRecitationInvitation_page-0001.jpg') }}" alt="Invitation Cover" />
        <div class="guest-info">
            <div class="guest-name">{{ $guest->full_name }}</div>
            <div class="reg-number">{{ $guest->registration_number }}</div>
        </div>
    </div>

    <!-- Second Page -->
    <div class="page">
        <img class="invitation-image" src="{{ public_path('QuranRecitationInvitation_page-0002.jpg') }}" alt="Invitation Details" />
    </div>
</body>
</html>