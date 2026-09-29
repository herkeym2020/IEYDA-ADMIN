# WhatsApp Business API Setup Guide

## Overview
This system uses WhatsApp Business API to send automated notifications to financial members alongside email notifications.

## Features
- ✅ Welcome messages (Email + WhatsApp) after form submission
- ✅ Monthly appreciation reminders (Email + WhatsApp)
- ✅ Re-engagement messages for inactive members (Email + WhatsApp)
- ✅ Payment reminders with bank details (Email + WhatsApp)

## Prerequisites
1. **Facebook Business Account** - [Create here](https://business.facebook.com)
2. **WhatsApp Business Account** - Must be verified
3. **Phone Number** - Dedicated business phone number (recommended)

---

## Step 1: Create Facebook Business Account

1. Go to [Meta Business Suite](https://business.facebook.com)
2. Click **Create Account**
3. Fill in your business details:
   - Business Name: **Ilorin Emirate Youths Development Association**
   - Your Name
   - Business Email
4. Complete verification process

---

## Step 2: Set Up WhatsApp Business API

### Option A: Using Facebook Developer Console (Free)

1. Go to [Facebook Developers](https://developers.facebook.com)
2. Click **My Apps** → **Create App**
3. Select **Business** type
4. Fill in app details:
   - App Name: `IEYDA Notifications`
   - App Contact Email: Your business email
   - Business Account: Select your business account
5. Click **Create App**

### Add WhatsApp Product

1. In your app dashboard, click **Add Product**
2. Find **WhatsApp** and click **Set Up**
3. Follow the setup wizard:
   - Select your Business Account
   - Add a phone number (or use test number initially)
   - Verify phone number via SMS/call

### Get API Credentials

1. In WhatsApp → **Getting Started**:
   - Copy your **Temporary Access Token** (valid 24 hours)
   - Note your **Phone Number ID**
   - Copy **WhatsApp Business Account ID**

2. For production, generate **Permanent Token**:
   - Go to **Settings** → **Business Settings**
   - Click **System Users** → **Add**
   - Create system user (e.g., `IEYDA API User`)
   - Assign **WhatsApp Business Management** permission
   - Click **Generate New Token**
   - Select your app and permissions: `whatsapp_business_messaging`
   - Copy and save the token securely ⚠️ (shown once!)

---

## Step 3: Configure Laravel Application

### Add to `.env` file:

```env
# WhatsApp Business API Configuration
WHATSAPP_API_URL=https://graph.facebook.com/v18.0/YOUR_PHONE_NUMBER_ID/messages
WHATSAPP_API_KEY=EAAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
WHATSAPP_FROM_NUMBER=234XXXXXXXXXX
```

### Replace Placeholders:

1. **YOUR_PHONE_NUMBER_ID**: 
   - Found in WhatsApp → API Setup → Phone Number ID
   - Example: `123456789012345`

2. **WHATSAPP_API_KEY**: 
   - Your permanent access token from Step 2
   - Example: `EAABwzLixnjYBO1...` (very long string)

3. **WHATSAPP_FROM_NUMBER**: 
   - Your WhatsApp Business phone number in international format (without + or spaces)
   - Nigerian example: `234XXXXXXXXXX` (replace 0 with 234)
   - Example: `2348012345678`

---

## Step 4: Configure Message Templates (Optional for Production)

For production WhatsApp API, you must create and get approval for message templates.

### Create Templates in Facebook Business Manager:

1. Go to **WhatsApp Manager** → **Message Templates**
2. Click **Create Template**
3. Create templates for:

#### Welcome Message Template
- **Name**: `welcome_message`
- **Category**: Utility
- **Language**: English
- **Content**:
```
Hello {{1}}! 🎉

Welcome to the Ilorin Emirate Youths Development Association (IEYDA)!

Thank you for joining our financial member program. Your contribution will help us:
💧 Provide clean water access
❤️ Support widows and vulnerable families  
🎓 Promote educational programs
📢 Raise community awareness

Your commitment makes a real difference in our community!

Best regards,
IEYDA Team
```

#### Monthly Reminder Template
- **Name**: `monthly_reminder`
- **Category**: Marketing
- **Language**: English
- **Content**:
```
Dear {{1}}, 🙏

Thank you for being a valued IEYDA financial member!

Your contributions continue to create positive impact in our community. Together, we're building a brighter future for the youth of Ilorin Emirate.

✅ Projects completed with your support
✅ Lives touched through our programs
✅ Communities transformed

Keep up the amazing work!

IEYDA Team
```

4. Submit for approval (usually 1-3 business days)

### Update Code to Use Templates:

After approval, modify [WhatsAppService.php](app/Services/WhatsAppService.php):

```php
// Instead of sending plain text, use template
Http::withToken(config('services.whatsapp.api_key'))
    ->post(config('services.whatsapp.api_url'), [
        'messaging_product' => 'whatsapp',
        'to' => $to,
        'type' => 'template',
        'template' => [
            'name' => 'welcome_message',
            'language' => ['code' => 'en'],
            'components' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $name]
                    ]
                ]
            ]
        ]
    ]);
```

---

## Step 5: Test the Integration

### Test Welcome Notification:

1. Submit a test form through Google Form
2. Check logs for WhatsApp API responses:
   ```bash
   php artisan tail
   ```
3. Verify email and WhatsApp message received

### Test Monthly Reminders Manually:

```bash
php artisan members:send-monthly-reminders
```

### Test with Sample Data:

```bash
# Add test member via tinker
php artisan tinker

$lead = App\Models\FinancialMemberLead::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '08012345678', // Will be converted to 2348012345678
    'amount' => 5000,
    'status' => 'converted'
]);

// Test notification
$service = app(App\Services\NotificationService::class);
$service->sendWelcomeNotification($lead);
```

---

## Step 6: Schedule Automated Reminders

Ensure Laravel scheduler is running. Add to your server crontab:

```bash
* * * * * cd /path/to/project/backend-fresh && php artisan schedule:run >> /dev/null 2>&1
```

The monthly reminders are configured to run on the **1st of every month at 9:00 AM**:

```php
// In app/Console/Kernel.php
$schedule->command('members:send-monthly-reminders')
    ->monthlyOn(1, '9:00');
```

---

## Troubleshooting

### Issue: "Invalid phone number" error

**Solution**: Ensure phone numbers are in international format without `+` or spaces:
- ✅ Correct: `2348012345678`
- ❌ Wrong: `+234 801 234 5678`, `08012345678`

The `WhatsAppService::cleanPhoneNumber()` method handles this automatically for Nigerian numbers.

### Issue: "Message not delivered"

**Possible causes**:
1. **Invalid token**: Generate new permanent token
2. **Template not approved**: Use freeform messages in test mode, or wait for template approval
3. **Phone number not registered**: Recipient must have WhatsApp installed
4. **Rate limits exceeded**: Facebook limits messages (1000/day for test accounts)

**Check delivery status**:
```php
// In WhatsAppService, add error logging
catch (\Exception $e) {
    Log::error('WhatsApp API Error', [
        'message' => $e->getMessage(),
        'response' => $response->json()
    ]);
}
```

### Issue: "Access token expired"

**Solution**: 
- Temporary tokens expire after 24 hours
- Use **System User Permanent Token** (see Step 2)
- Permanent tokens don't expire but can be revoked

### Issue: "Template not found"

**Solution**: 
- During development, use test numbers (no templates required)
- For production, create and approve templates first
- Or use Cloud API which allows freeform messages

---

## Production Checklist

- [ ] Created Facebook Business Account
- [ ] Set up WhatsApp Business API
- [ ] Generated permanent access token
- [ ] Added credentials to `.env`
- [ ] Created and approved message templates (if required)
- [ ] Tested welcome notifications
- [ ] Tested monthly reminders manually
- [ ] Configured Laravel scheduler cron job
- [ ] Set up error logging and monitoring
- [ ] Verified phone number formatting works correctly
- [ ] Tested with real recipient phone numbers

---

## API Rate Limits

### Test Accounts (Free):
- **1,000 messages per day**
- **Limited to 50 phone numbers**
- **250 messages per hour per phone number**

### Production (Paid):
- **Unlimited messages** (pay per message)
- **Tiered pricing** based on country
- **Higher rate limits**

### Pricing (Nigeria):
- **Utility messages**: ~$0.006 per message
- **Marketing messages**: ~$0.012 per message
- **Service messages**: ~$0.003 per message

---

## Additional Resources

- [WhatsApp Business API Documentation](https://developers.facebook.com/docs/whatsapp)
- [WhatsApp Cloud API Getting Started](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started)
- [Message Templates Guidelines](https://developers.facebook.com/docs/whatsapp/message-templates/guidelines)
- [WhatsApp Business Policy](https://www.whatsapp.com/legal/business-policy)

---

## Support

For issues with:
- **Laravel integration**: Check [NotificationService.php](app/Services/NotificationService.php) and [WhatsAppService.php](app/Services/WhatsAppService.php)
- **WhatsApp API**: Contact [Meta Business Support](https://business.facebook.com/business/help)
- **Message templates**: Check template status in WhatsApp Manager

---

**Last Updated**: January 2025  
**Version**: 1.0
