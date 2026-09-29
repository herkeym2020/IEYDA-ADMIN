<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\GuestRegistrationController;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class GuestRegistrationAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_registration_settings_can_be_updated(): void
    {
        $controller = new GuestRegistrationController();
        $request = new Request([
            'registration_limit' => 250,
            'device_restriction_enabled' => '1',
        ]);

        $response = $controller->updateSettings($request);

        $this->assertSame('250', Setting::get('guest_registration_limit'));
        $this->assertSame('1', Setting::get('guest_registration_device_restriction_enabled'));
        $this->assertTrue($response->getSession()->has('success'));
    }
}
