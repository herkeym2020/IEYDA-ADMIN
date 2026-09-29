# 🎉 Notification System - Quick Start Guide

## What We Built

A complete **dual-channel automated notification system** that sends both **Email** and **WhatsApp** messages to IEYDA financial members.

---

## 🚀 Quick Setup (3 Steps)

### Step 1: Configure WhatsApp API

Add to your `.env` file:

```env
# WhatsApp Business API
WHATSAPP_API_URL=https://graph.facebook.com/v18.0/YOUR_PHONE_NUMBER_ID/messages
WHATSAPP_API_KEY=your_whatsapp_business_api_token
WHATSAPP_FROM_NUMBER=234XXXXXXXXXX
```

**How to get these values?** See [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md) for complete guide.

### Step 2: Test the System

```bash
# Test welcome notification
php artisan tinker

$service = app(App\Services\NotificationService::class);
$member = App\Models\FinancialMemberLead::first();
$service->sendWelcomeNotification($member);
```

### Step 3: Set Up Scheduler (Production)

Add to server crontab:
```bash
* * * * * cd /path/to/backend-fresh && php artisan schedule:run >> /dev/null 2>&1
```

✅ **Done!** Your notification system is now active.

---

## 📋 What Happens Automatically

### When Someone Becomes a Financial Member:
1. They complete payment via Paystack ✅
2. Redirected to Google Form ✅
3. Submit form ✅
4. **Instantly receive**:
   - ✉️ Welcome email (HTML with IEYDA colors)
   - 📱 Welcome WhatsApp message (with emojis)

### Every 1st of the Month at 9:00 AM:
- All active members get **appreciation message** (Email + WhatsApp)
- Inactive members (60+ days) get **re-engagement message** (Email + WhatsApp)

---

## 📱 Message Examples

### Welcome Message (WhatsApp)
```
Hello John Doe! 🎉

Welcome to the Ilorin Emirate Youths 
Development Association (IEYDA)!

Thank you for joining our financial 
member program. Your contribution 
will help us:

💧 Provide clean water access
❤️ Support widows and vulnerable families
🎓 Promote educational programs
📢 Raise community awareness

Your commitment makes a real 
difference in our community!

Best regards,
IEYDA Team
```

### Monthly Appreciation (WhatsApp)
```
Dear John Doe, 🙏

Thank you for being a valued IEYDA 
financial member!

Your contributions continue to create 
positive impact in our community. 
Together, we're building a brighter 
future for the youth of Ilorin Emirate.

✅ Projects completed with your support
✅ Lives touched through our programs
✅ Communities transformed

Keep up the amazing work!

IEYDA Team
```

---

## 🧪 Testing Commands

### Run Full Test Suite
```bash
php artisan tinker < test-notifications.php
```

### Test Individual Components
```bash
php artisan tinker

# Test WhatsApp formatting
$whatsapp = app(App\Services\WhatsAppService::class);
$whatsapp->cleanPhoneNumber('08012345678');
// Output: 2348012345678

# Test welcome notification
$service = app(App\Services\NotificationService::class);
$member = App\Models\FinancialMemberLead::first();
$service->sendWelcomeNotification($member);

# Test monthly reminders
php artisan members:send-monthly-reminders
```

---

## 📁 Key Files

| File | Purpose |
|------|---------|
| [WhatsAppService.php](app/Services/WhatsAppService.php) | WhatsApp API integration |
| [NotificationService.php](app/Services/NotificationService.php) | Coordinates Email + WhatsApp |
| [SendMonthlyReminders.php](app/Console/Commands/SendMonthlyReminders.php) | Monthly scheduler |
| [FinancialMemberLeadController.php](app/Http/Controllers/Api/FinancialMemberLeadController.php) | Form webhook handler |
| [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md) | Complete WhatsApp setup guide |
| [NOTIFICATION_SYSTEM.md](NOTIFICATION_SYSTEM.md) | Detailed system docs |

---

## 🎯 System Features

✅ **Dual Channel**: Email + WhatsApp  
✅ **Instant Welcome**: Sent immediately after form submission  
✅ **Monthly Reminders**: Automated appreciation messages  
✅ **Smart Re-engagement**: Targets inactive members (60+ days)  
✅ **Nigerian Phone Support**: Auto-formats 08xxx to 234xxx  
✅ **HTML Emails**: Professional templates with IEYDA colors  
✅ **Emoji-Rich WhatsApp**: Engaging messages with icons  
✅ **Activity Logging**: Track all notifications sent  
✅ **Error Handling**: Graceful failure with detailed logs  

---

## 🔧 Troubleshooting

### WhatsApp messages not sending?
1. Check `.env` has correct credentials
2. Verify phone numbers format: `234XXXXXXXXXX` (no + or spaces)
3. Check Laravel logs: `php artisan tail`
4. Test API token: See [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md#troubleshooting)

### Emails not sending?
1. Verify Gmail SMTP credentials in `.env`
2. Use App Password (not regular password)
3. Test: `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('test@example.com'))`

### Monthly reminders not running?
1. Check cron job: `crontab -l`
2. Test manually: `php artisan members:send-monthly-reminders`
3. Check scheduler: `php artisan schedule:list`

---

## 📚 Documentation

- **Quick Start**: This file
- **WhatsApp Setup**: [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md) - Complete WhatsApp configuration
- **System Docs**: [NOTIFICATION_SYSTEM.md](NOTIFICATION_SYSTEM.md) - Architecture & API reference
- **Project README**: [README.md](README.md) - Full project documentation

---

## ✅ Production Checklist

- [ ] Add WhatsApp credentials to `.env`
- [ ] Test welcome notification works
- [ ] Test monthly reminders manually
- [ ] Set up server cron job for scheduler
- [ ] Verify phone number formatting works
- [ ] Check activity logs are being created
- [ ] Test with real member data
- [ ] Monitor Laravel logs for errors

---

## 🎉 What's Next?

1. **Configure WhatsApp** → [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md)
2. **Test the system** → Run commands above
3. **Go live!** → Set up cron job and monitor

---

**Questions?** Check the comprehensive guides:
- [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md) - WhatsApp configuration
- [NOTIFICATION_SYSTEM.md](NOTIFICATION_SYSTEM.md) - System architecture

---

Built with ❤️ for IEYDA
