# IEYDA Backend - Laravel Application

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## About This Project

This is the backend application for the **Ilorin Emirate Youths Development Association (IEYDA)** management system. Built with Laravel 10, it provides API endpoints, admin dashboard, and automated communication systems for managing youth programs, financial memberships, and community initiatives.

## Key Features

### 🎯 Financial Member Management
- Online payment integration with Paystack
- Automated Google Form integration for member data collection
- Professional payment page with IEYDA branding
- Real-time payment verification and callback handling

### 📧 Automated Notification System (NEW!)
- **Dual-channel notifications**: Email + WhatsApp
- **Welcome messages**: Sent immediately after form submission
- **Monthly reminders**: Scheduled appreciation and re-engagement messages
- **Payment reminders**: Upcoming contribution notifications
- Smart message selection based on member activity

### 🔔 Notification Channels
- **Email**: Professional HTML templates with brand colors
- **WhatsApp**: Emoji-rich messages via WhatsApp Business API
- **Activity Logging**: Track all notification history

## Documentation

- 📱 [WhatsApp Setup Guide](WHATSAPP_SETUP.md) - Complete guide for WhatsApp Business API setup
- 📧 [Notification System](NOTIFICATION_SYSTEM.md) - Detailed notification system documentation
- 🎨 [Edit Pages Status](EDIT_PAGES_STATUS.md) - Frontend page editing guide
- 📄 [Views Documentation](VIEWS_DOCUMENTATION.md) - Blade template documentation

## Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 5.7+
- Node.js & npm (for asset compilation)

### Installation

1. **Clone and install dependencies**
   ```bash
   cd backend-fresh
   composer install
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Update `.env` with your credentials**
   ```env
   # Database
   DB_DATABASE=ieyda_cms
   DB_USERNAME=root
   DB_PASSWORD=your_password

   # Email (Gmail SMTP)
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-app-password
   MAIL_FROM_ADDRESS=your-email@gmail.com

   # Paystack (Nigeria)
   PAYSTACK_PUBLIC_KEY=pk_test_xxxx
   PAYSTACK_SECRET_KEY=sk_test_xxxx

   # WhatsApp Business API (See WHATSAPP_SETUP.md)
   WHATSAPP_API_URL=https://graph.facebook.com/v18.0/YOUR_PHONE_ID/messages
   WHATSAPP_API_KEY=your_whatsapp_token
   WHATSAPP_FROM_NUMBER=234XXXXXXXXXX
   ```

4. **Run migrations**
   ```bash
   php artisan migrate
   ```

5. **Start development server**
   ```bash
   php artisan serve
   # Visit: http://localhost:8000
   ```

### Configure Task Scheduler (Production)

Add to your server's crontab:
```bash
* * * * * cd /path/to/backend-fresh && php artisan schedule:run >> /dev/null 2>&1
```

This enables:
- Monthly reminder emails/WhatsApp (1st of month at 9:00 AM)
- Scheduled content publishing
- Other automated tasks

## Testing Notifications

### Test Welcome Notification
```bash
php artisan tinker

$service = app(App\Services\NotificationService::class);
$member = App\Models\FinancialMemberLead::first();
$service->sendWelcomeNotification($member);
```

### Test Monthly Reminders
```bash
# Run manually
php artisan members:send-monthly-reminders

# Check output
# Starting monthly reminders...
# Sent: 10, Failed: 0
# Completed!
```

### Run Test Script
```bash
php artisan tinker < test-notifications.php
```

## API Endpoints

### Financial Members

- `POST /api/v1/financial-member/submit` - Google Form webhook
- `GET /financial-member` - Payment page
- `POST /financial-member/initialize` - Initialize Paystack payment
- `GET /financial-member/callback` - Payment callback

## Project Structure

```
backend-fresh/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SendMonthlyReminders.php    # Monthly notification scheduler
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       │   └── FinancialMemberLeadController.php  # Form webhook
│   │       └── MembershipPaymentController.php        # Payment handling
│   ├── Models/
│   │   ├── FinancialMemberLead.php         # Financial member model
│   │   └── MembershipPayment.php           # Payment records
│   └── Services/
│       ├── NotificationService.php         # Notification coordinator
│       └── WhatsAppService.php             # WhatsApp integration
├── config/
│   └── services.php                        # WhatsApp, Paystack config
├── resources/
│   └── views/
│       └── membership/
│           └── payment.blade.php           # Professional payment page
├── routes/
│   ├── web.php                             # Web routes
│   └── api.php                             # API routes
├── WHATSAPP_SETUP.md                       # WhatsApp setup guide
├── NOTIFICATION_SYSTEM.md                  # Notification docs
└── test-notifications.php                  # Test script
```

## Tech Stack

- **Framework**: Laravel 10
- **PHP**: 8.2+
- **Database**: MySQL
- **Payment**: Paystack API (Nigeria)
- **Email**: Gmail SMTP
- **WhatsApp**: WhatsApp Business API (Facebook Graph API)
- **Scheduler**: Laravel Task Scheduler
- **Frontend**: Blade templates + TailwindCSS

## Development

### Clear caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### View logs
```bash
php artisan tail
# or
tail -f storage/logs/laravel.log
```

### Database operations
```bash
# Fresh migration
php artisan migrate:fresh

# Seed data
php artisan db:seed

# Rollback
php artisan migrate:rollback
```

## Contributing

1. Create a feature branch
2. Make your changes
3. Test thoroughly
4. Submit a pull request

## Support

For technical issues:
- Check [NOTIFICATION_SYSTEM.md](NOTIFICATION_SYSTEM.md) for notification setup
- Check [WHATSAPP_SETUP.md](WHATSAPP_SETUP.md) for WhatsApp configuration
- Review Laravel logs in `storage/logs/`
- Contact development team

## License

This project is proprietary software for IEYDA.

---

Built with ❤️ for the Ilorin Emirate Youths Development Association

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
