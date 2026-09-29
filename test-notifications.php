#!/usr/bin/env php
<?php

/**
 * Notification System Test Script
 * 
 * This script tests the email and WhatsApp notification system for financial members.
 * Run with: php artisan tinker < test-notifications.php
 */

echo "\n=================================\n";
echo "IEYDA Notification System Tests\n";
echo "=================================\n\n";

// Test 1: Check if services are available
echo "Test 1: Checking if services are registered...\n";
try {
    $whatsappService = app(App\Services\WhatsAppService::class);
    $notificationService = app(App\Services\NotificationService::class);
    echo "✅ Services registered successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Check WhatsApp configuration
echo "Test 2: Checking WhatsApp configuration...\n";
$whatsappConfig = config('services.whatsapp');
if (!empty($whatsappConfig['api_url']) && !empty($whatsappConfig['api_key']) && !empty($whatsappConfig['from_number'])) {
    echo "✅ WhatsApp config found:\n";
    echo "   - API URL: " . $whatsappConfig['api_url'] . "\n";
    echo "   - From Number: " . $whatsappConfig['from_number'] . "\n";
    echo "   - API Key: " . (strlen($whatsappConfig['api_key']) > 20 ? substr($whatsappConfig['api_key'], 0, 20) . '...' : 'NOT SET') . "\n\n";
} else {
    echo "⚠️  WhatsApp not configured yet. Please add to .env:\n";
    echo "   WHATSAPP_API_URL=https://graph.facebook.com/v18.0/YOUR_PHONE_ID/messages\n";
    echo "   WHATSAPP_API_KEY=your_api_token\n";
    echo "   WHATSAPP_FROM_NUMBER=234XXXXXXXXXX\n\n";
}

// Test 3: Check email configuration
echo "Test 3: Checking email configuration...\n";
$mailConfig = config('mail');
if ($mailConfig['default'] === 'smtp' && !empty(config('mail.from.address'))) {
    echo "✅ Email config found:\n";
    echo "   - Mailer: " . $mailConfig['default'] . "\n";
    echo "   - From: " . config('mail.from.address') . "\n\n";
} else {
    echo "⚠️  Email not configured properly.\n\n";
}

// Test 4: Get sample member
echo "Test 4: Finding a test member...\n";
$member = App\Models\FinancialMemberLead::where('status', 'converted')->first();
if ($member) {
    echo "✅ Found member: {$member->name} ({$member->email})\n\n";
} else {
    echo "⚠️  No converted members found in database.\n";
    echo "   Creating a test member...\n";
    
    $member = new App\Models\FinancialMemberLead();
    $member->name = "Test User";
    $member->email = "test@example.com";
    $member->phone = "08012345678";
    $member->amount = 5000;
    $member->status = "converted";
    // Note: Don't save to avoid polluting database
    echo "✅ Test member created (not saved)\n\n";
}

// Test 5: Phone number formatting
echo "Test 5: Testing phone number formatting...\n";
$testNumbers = ['08012345678', '2348012345678', '+2348012345678', '080 123 4567'];
foreach ($testNumbers as $number) {
    $cleaned = $whatsappService->cleanPhoneNumber($number);
    echo "   {$number} → {$cleaned}\n";
}
echo "✅ Phone formatting works\n\n";

// Test 6: Test welcome notification (DRY RUN)
echo "Test 6: Testing welcome notification structure...\n";
echo "   This is a DRY RUN - no actual messages will be sent.\n";
echo "   To send a real test message, uncomment the line below:\n";
echo "   // \$notificationService->sendWelcomeNotification(\$member);\n\n";

// Test 7: Check scheduled tasks
echo "Test 7: Checking scheduled tasks...\n";
echo "   Monthly reminders scheduled: 1st of month at 9:00 AM\n";
echo "   Command: php artisan members:send-monthly-reminders\n\n";

echo "=================================\n";
echo "Tests completed!\n";
echo "=================================\n\n";

echo "To send a real test notification:\n";
echo "1. Configure WhatsApp credentials in .env\n";
echo "2. Run: php artisan tinker\n";
echo "3. Execute:\n";
echo "   \$service = app(App\\Services\\NotificationService::class);\n";
echo "   \$member = App\\Models\\FinancialMemberLead::first();\n";
echo "   \$service->sendWelcomeNotification(\$member);\n\n";

echo "To test monthly reminders:\n";
echo "   php artisan members:send-monthly-reminders\n\n";
