# Payment-Gated Google Form System - Setup Guide

## Overview
This system adds payment verification before Google Form access and automated email campaigns for financial members.

## What Was Built

### 1. **Payment Gateway Integration**
- **Payment Page**: `/membership/payment` - Beautiful landing page with tier selection
- **Tiers**: Bronze (₦5,000), Silver (₦10,000), Gold (₦25,000), Premium (₦50,000)
- **Payment Processor**: Paystack integration
- **Flow**: User pays → Redirected to Google Form with unique token

### 2. **Database Table**
- **membership_payments**: Stores payment records
  - Reference, email, name, phone, amount, status
  - Unique form_access_token for each payment
  - Payment verification data from Paystack

### 3. **Automated Email System**

#### Welcome Email (Immediate)
- Sent automatically when Google Form is submitted
- Thanks user for becoming a member
- Explains next steps

#### Monthly Reminders (Scheduled)
- Runs on 1st of each month at 9 AM
- Two types:
  - **Appreciation Email**: For active members
  - **Engagement Email**: For members inactive for 60+ days

### 4. **Routes Added**
```
GET  /membership/payment - Show payment page
POST /membership/payment/initiate - Initialize Paystack payment  
GET  /membership/payment/callback - Handle payment verification
```

## Setup Instructions

### Step 1: Update .env File
```env
# Add your Paystack keys
PAYSTACK_PUBLIC_KEY=pk_test_xxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxx

# Add your Google Form URL
GOOGLE_FORM_URL=https://docs.google.com/forms/d/e/YOUR_FORM_ID/viewform
```

### Step 2: Run Migration
```bash
cd C:\Users\WINDOWS\Downloads\project1\backend-fresh
php artisan migrate --path=database/migrations/2026_01_14_142000_create_membership_payments_table.php
```

### Step 3: Configure Laravel Scheduler
Add this to your server's crontab (or Windows Task Scheduler):
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Step 4: Update Google Form Prefill
Your Google Form should accept these URL parameters:
- `token` - Unique access token from payment
- `email` - User's email address

Example: `https://docs.google.com/forms/d/e/FORM_ID/viewform?entry.12345=token&entry.67890=email`

### Step 5: Test the Flow

1. **Visit Payment Page**: 
   - Go to: `https://your-domain.com/membership/payment`
   
2. **Make Test Payment**:
   - Use Paystack test card: `4084084084084081`
   - CVV: `408`, Expiry: Any future date
   - PIN: `0000`, OTP: `123456`

3. **Verify Redirect**:
   - After payment, should redirect to Google Form
   - Form should have email pre-filled

4. **Check Emails**:
   - Submit form
   - Welcome email should arrive within minutes

5. **Test Monthly Reminders** (Manual):
   ```bash
   php artisan members:send-monthly-reminders
   ```

## How It Works

### Payment Flow
```
User visits /membership/payment
    ↓
Selects tier & enters details
    ↓
System creates payment record (status: pending)
    ↓
Redirects to Paystack payment page
    ↓
User completes payment
    ↓
Paystack redirects to /membership/payment/callback
    ↓
System verifies payment with Paystack API
    ↓
Updates payment status to 'completed'
    ↓
Generates unique form_access_token
    ↓
Redirects to Google Form with token & email
```

### Google Form Submission Flow
```
User submits Google Form
    ↓
Google Apps Script sends data to:
POST /api/financial-member/submit
    ↓
Backend creates FinancialMemberLead record
    ↓
Sends welcome email immediately
    ↓
Logs activity
```

### Monthly Email Flow
```
Scheduler runs on 1st of month at 9 AM
    ↓
Command: members:send-monthly-reminders
    ↓
Gets all 'converted' status members
    ↓
For each member:
    - Check last_interaction_at
    - If inactive 60+ days → Engagement email
    - Otherwise → Appreciation email
    ↓
Logs activity for each email sent
```

## Email Templates

### Welcome Email (Sent immediately)
- Subject: "Welcome to IEYDA Financial Membership!"
- Content: Thanks, explains benefits, next steps

### Appreciation Email (Monthly - Active members)
- Subject: "Thank You for Your Continued Support - IEYDA"
- Content: Gratitude, community impact

### Engagement Email (Monthly - Inactive 60+ days)
- Subject: "We Miss You! - IEYDA Update"
- Content: Reconnection, invitation to engage

## Admin Features

### View All Payments
Go to admin dashboard and run:
```php
// In tinker or controller
MembershipPayment::orderBy('created_at', 'desc')->get();
```

### Manually Send Monthly Reminders
```bash
php artisan members:send-monthly-reminders
```

### Check Payment Status
```php
$payment = MembershipPayment::where('email', 'user@example.com')->first();
echo $payment->status; // pending|completed|failed
```

## Security Features

1. **Webhook Token**: Google Form webhook still uses `LEAD_WEBHOOK_TOKEN`
2. **Payment Verification**: All payments verified with Paystack API
3. **Unique Tokens**: Each payment gets unique form_access_token
4. **CSRF Protection**: All forms include CSRF tokens

## Troubleshooting

### Emails Not Sending
1. Check .env mail configuration
2. Run: `php artisan config:clear`
3. Check logs: `storage/logs/laravel.log`

### Payment Not Completing
1. Verify Paystack keys in .env
2. Check callback URL is publicly accessible
3. Review Paystack dashboard for transaction status

### Monthly Reminders Not Running
1. Verify cron/scheduler is configured
2. Test manually: `php artisan members:send-monthly-reminders`
3. Check logs for errors

## Files Created/Modified

### New Files
- `app/Http/Controllers/MembershipPaymentController.php`
- `app/Models/MembershipPayment.php`
- `app/Console/Commands/SendMonthlyReminders.php`
- `resources/views/membership/payment.blade.php`
- `database/migrations/2026_01_14_142000_create_membership_payments_table.php`

### Modified Files
- `config/services.php` - Added Paystack & Google Form config
- `app/Console/Kernel.php` - Added monthly reminder schedule
- `app/Http/Controllers/Api/FinancialMemberLeadController.php` - Added welcome email
- `routes/web.php` - Added payment routes

## Next Steps

1. ✅ Run migration
2. ✅ Update .env with Paystack keys
3. ✅ Add Google Form URL to .env
4. ✅ Configure server cron for scheduler
5. ✅ Test payment flow
6. ✅ Customize email templates if needed
7. ✅ Go live!

## Support
For issues or questions, check the Laravel logs at `storage/logs/laravel.log`
