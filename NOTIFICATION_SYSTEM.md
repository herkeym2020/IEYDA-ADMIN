# Financial Member Notification System

## Overview
Automated dual-channel notification system for IEYDA financial members using **Email + WhatsApp**.

---

## 📋 Features Implemented

### 1. Welcome Notifications (Immediate)
**Trigger**: After financial member form submission  
**Channels**: Email + WhatsApp  
**Template**: Professional HTML email + emoji-rich WhatsApp message  

**Content**:
- Welcome greeting with member name
- Program benefits (Water, Widows, Education, Awareness)
- Thank you message
- IEYDA branding with green/gold colors

**Files**:
- [FinancialMemberLeadController.php](app/Http/Controllers/Api/FinancialMemberLeadController.php) - Handles form webhook
- [NotificationService.php](app/Services/NotificationService.php) - Coordinates delivery

### 2. Monthly Reminders (Scheduled)
**Trigger**: 1st of every month at 9:00 AM  
**Channels**: Email + WhatsApp  
**Types**: 
- **Appreciation** (active members) - Thanking for continued support
- **Re-engagement** (inactive 60+ days) - Encouraging reconnection

**Content**:
- Personalized greeting
- Impact summary
- Community achievements
- Call to action

**Files**:
- [SendMonthlyReminders.php](app/Console/Commands/SendMonthlyReminders.php) - Scheduled command
- Kernel.php - Scheduler configuration

### 3. Payment Reminders (Future Enhancement)
**Status**: Service method ready, scheduler not yet created  
**Template**: `NotificationService::sendPaymentReminder($member, $amount)`

---

## 🏗️ Architecture

### Service Layer Pattern

```
Controller/Command
       ↓
NotificationService (Coordinator)
       ↓
  ┌────┴────┐
  ↓         ↓
Email    WhatsApp
Service   Service
```

### Key Components:

#### 1. **WhatsAppService** ([WhatsAppService.php](app/Services/WhatsAppService.php))
- Facebook Graph API integration
- Phone number formatting (Nigerian +234)
- Message templates with emojis
- Error handling and logging

**Methods**:
```php
sendMessage($to, $message)              // Core API call
cleanPhoneNumber($phone)                // 0xxx → 234xxx
sendWelcomeMessage($name, $phone)       // 🎉 Welcome
sendAppreciationReminder($name, $phone) // 🙏 Monthly thanks
sendEngagementReminder($name, $phone)   // 👋 Re-engagement
sendPaymentReminder($name, $phone, $amt)// 🔔 Payment reminder
```

#### 2. **NotificationService** ([NotificationService.php](app/Services/NotificationService.php))
- Coordinates email + WhatsApp delivery
- HTML email templates with IEYDA brand colors
- Error handling for both channels

**Methods**:
```php
sendWelcomeNotification($member)        // Welcome (both channels)
sendMonthlyReminder($member, $type)     // Monthly (appreciation/engagement)
sendPaymentReminder($member, $amount)   // Payment reminder (both channels)
```

---

## ⚙️ Configuration

### Environment Variables (.env)

```env
# Email (Gmail SMTP - Already configured)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="IEYDA"

# WhatsApp Business API (NEW - Requires setup)
WHATSAPP_API_URL=https://graph.facebook.com/v18.0/YOUR_PHONE_NUMBER_ID/messages
WHATSAPP_API_KEY=your_whatsapp_business_api_token
WHATSAPP_FROM_NUMBER=234XXXXXXXXXX
```

### Services Config ([config/services.php](config/services.php))

```php
'whatsapp' => [
    'api_url' => env('WHATSAPP_API_URL'),
    'api_key' => env('WHATSAPP_API_KEY'),
    'from_number' => env('WHATSAPP_FROM_NUMBER'),
]
```

---

## 🚀 Usage

### Trigger Welcome Notification
Automatically sent when form is submitted via Google Form webhook:

```php
// In FinancialMemberLeadController@store
$lead = FinancialMemberLead::create([...]);

$this->notificationService->sendWelcomeNotification($lead);
// Sends both email and WhatsApp
```

### Trigger Monthly Reminders
Run manually or via scheduler:

```bash
# Manual execution
php artisan members:send-monthly-reminders

# Output:
# Starting monthly reminders...
# Sent: 45, Failed: 0
# Completed!
```

Automated via scheduler (1st of month, 9:00 AM):
```php
// In app/Console/Kernel.php
$schedule->command('members:send-monthly-reminders')
    ->monthlyOn(1, '9:00');
```

### Test Notifications

```bash
php artisan tinker

# Create test member
$member = App\Models\FinancialMemberLead::first();

# Test welcome
$service = app(App\Services\NotificationService::class);
$service->sendWelcomeNotification($member);

# Test monthly reminder
$service->sendMonthlyReminder($member, 'appreciation');
```

---

## 📱 WhatsApp Message Examples

### Welcome Message
```
Hello John Doe! 🎉

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

### Appreciation Reminder
```
Dear John Doe, 🙏

Thank you for being a valued IEYDA financial member!

Your contributions continue to create positive impact in our community. Together, we're building a brighter future for the youth of Ilorin Emirate.

✅ Projects completed with your support
✅ Lives touched through our programs
✅ Communities transformed

Keep up the amazing work!

IEYDA Team
```

### Payment Reminder
```
Hello John Doe! 🔔

This is a friendly reminder about your monthly contribution to IEYDA.

Amount: ₦5,000

Bank Details:
💳 Bank: Access Bank
💳 Account: 1234567890
💳 Name: IEYDA

Your support continues to transform lives in our community!

Thank you,
IEYDA Team
```

---

## 📧 Email Examples

All emails use professional HTML templates with:
- **Header**: Green gradient with gold accents
- **Content**: Clean white background with IEYDA colors
- **Footer**: Organization details and mission statement
- **Responsive**: Mobile-friendly design

**Brand Colors**:
- Primary Green: `#2E7D32`
- Secondary Gold: `#FFB300`
- Accent Blue: `#1976D2`

---

## 🔍 Monitoring & Logging

### Activity Logging
All notifications are logged in `financial_lead_activities` table:

```php
$member->logActivity('welcome_notification_sent', 'Welcome notification sent (Email + WhatsApp)');
$member->logActivity('monthly_notification_sent', 'Monthly appreciation notification sent');
```

### View Activity History
```bash
php artisan tinker

$member = App\Models\FinancialMemberLead::first();
$member->activities()->latest()->get();
```

### Error Logging
Check Laravel logs for failed notifications:

```bash
tail -f storage/logs/laravel.log

# Or use artisan
php artisan tail
```

---

## 📊 Database Schema

### Financial Member Lead
```php
Schema::create('financial_member_leads', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('phone');
    $table->decimal('amount', 10, 2);
    $table->string('status'); // pending, converted, declined
    $table->timestamp('last_interaction_at')->nullable();
    $table->timestamps();
});
```

### Activity Log
```php
Schema::create('financial_lead_activities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lead_id');
    $table->string('activity_type'); // welcome_notification_sent, monthly_notification_sent
    $table->text('description')->nullable();
    $table->timestamps();
});
```

---

## ✅ Testing Checklist

### Pre-deployment Tests

- [ ] **WhatsApp API**: Verify credentials in `.env`
- [ ] **Email SMTP**: Test email sending works
- [ ] **Phone Formatting**: Test with various formats (0xxx, 234xxx)
- [ ] **Welcome Flow**: Submit test form → Verify email + WhatsApp
- [ ] **Monthly Reminder**: Run command manually → Check delivery
- [ ] **Error Handling**: Test with invalid phone/email → Check logs
- [ ] **Activity Logging**: Verify activities table records sent notifications
- [ ] **Scheduler**: Confirm cron job runs Laravel scheduler

### Test Commands

```bash
# Test email configuration
php artisan tinker
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });

# Test WhatsApp (after setup)
$whatsapp = app(App\Services\WhatsAppService::class);
$whatsapp->sendMessage('2348012345678', 'Test message');

# Test full notification flow
$service = app(App\Services\NotificationService::class);
$member = App\Models\FinancialMemberLead::first();
$service->sendWelcomeNotification($member);

# Test monthly reminders
php artisan members:send-monthly-reminders
```

---

## 🛠️ Setup Steps

1. **Configure Email** (Already done)
   - Gmail SMTP credentials in `.env`
   - Test with sample email

2. **Set Up WhatsApp Business API** (Required)
   - Follow [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md)
   - Create Facebook Business account
   - Get API credentials
   - Add to `.env`

3. **Configure Scheduler** (Production)
   ```bash
   # Add to server crontab
   * * * * * cd /path/to/backend-fresh && php artisan schedule:run >> /dev/null 2>&1
   ```

4. **Test System**
   - Submit test form
   - Verify welcome notifications
   - Run monthly command manually
   - Check logs for errors

---

## 📁 Key Files

### Services
- [WhatsAppService.php](app/Services/WhatsAppService.php) - WhatsApp API integration
- [NotificationService.php](app/Services/NotificationService.php) - Notification coordinator

### Controllers
- [FinancialMemberLeadController.php](app/Http/Controllers/Api/FinancialMemberLeadController.php) - Form webhook

### Commands
- [SendMonthlyReminders.php](app/Console/Commands/SendMonthlyReminders.php) - Monthly scheduler

### Configuration
- [services.php](config/services.php) - WhatsApp config
- [.env.example](.env.example) - Environment template

### Documentation
- [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md) - Complete WhatsApp setup guide
- [NOTIFICATION_SYSTEM.md](NOTIFICATION_SYSTEM.md) - This file

---

## 🚨 Troubleshooting

### WhatsApp Not Sending

**Check**:
1. API credentials in `.env`
2. Phone number format (234xxx, no +)
3. WhatsApp Business API status
4. Rate limits (1000/day for test accounts)

**Debug**:
```bash
php artisan tinker

$whatsapp = app(App\Services\WhatsAppService::class);
$response = $whatsapp->sendMessage('2348012345678', 'Test');
dd($response);
```

### Email Not Sending

**Check**:
1. SMTP credentials
2. Gmail app password (not regular password)
3. Less secure apps enabled (if using regular Gmail)
4. Firewall/port 587 open

**Debug**:
```bash
php artisan tinker

Mail::raw('Test', function($m) { 
    $m->to('test@example.com')
      ->subject('Test'); 
});
```

### Scheduler Not Running

**Check**:
1. Cron job configured: `crontab -l`
2. Laravel logs: `storage/logs/laravel.log`
3. Command works manually: `php artisan members:send-monthly-reminders`

---

## 🎯 Future Enhancements

- [ ] Create payment reminder scheduler (weekly/monthly)
- [ ] Add SMS fallback for failed WhatsApp delivery
- [ ] Create admin dashboard to view notification history
- [ ] Add notification preferences (email only, WhatsApp only, both)
- [ ] Implement message delivery status tracking
- [ ] Add A/B testing for message templates
- [ ] Create notification analytics (open rates, click rates)
- [ ] Add multi-language support (Yoruba, Hausa)

---

**System Status**: ✅ Fully Implemented  
**Last Updated**: January 2025  
**Version**: 1.0
