<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">{{ $organizationName }}</h1>
        <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Response to Your Message</p>
    </div>

    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb;">
        <p style="margin: 0 0 20px 0; font-size: 16px;">
            Dear <strong>{{ $senderName }}</strong>,
        </p>

        <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6;">
            Thank you for your message regarding "<strong>{{ $subject }}</strong>". We have reviewed your inquiry and wanted to provide you with the following response:
        </p>

        <div style="background: white; padding: 20px; border-left: 4px solid #10b981; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; font-size: 14px; line-height: 1.8; white-space: pre-wrap;">{{ $replyText }}</p>
        </div>

        <p style="margin: 20px 0 0 0; font-size: 14px; color: #666; line-height: 1.6;">
            If you have any further questions or need additional information, please feel free to reach out to us.<br><br>
            Best regards,<br>
            <strong>{{ $organizationName }} Team</strong>
        </p>
    </div>

    <div style="background: #1f2937; color: #9ca3af; padding: 20px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px;">
        <p style="margin: 0;">© {{ date('Y') }} {{ $organizationName }}. All rights reserved.</p>
    </div>
    </div>
</body>
</html>
