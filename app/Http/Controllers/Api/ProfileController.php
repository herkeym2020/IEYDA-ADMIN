<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        $user->update($validated);
        return new UserResource($user);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'The current password is incorrect.'], 422);
        }
        $user->update(['password' => Hash::make($validated['password'])]);
        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function setup2fa(Request $request)
    {
        $user = $request->user();
        $secret = Google2FA::generateSecretKey();
        $qr = Google2FA::getQRCodeInline(config('app.name'), $user->email, $secret);
        $request->session()->put('2fa_secret', $secret);
        return response()->json(['secret' => $secret, 'qr' => $qr]);
    }

    public function enable2fa(Request $request)
    {
        $user = $request->user();
        $secret = $request->input('secret') ?? $request->session()->get('2fa_secret');
        $otp = $request->input('otp');
        if (!Google2FA::verifyKey($secret, $otp)) {
            return response()->json(['message' => 'Invalid OTP code.'], 422);
        }
        $user->google2fa_secret = $secret;
        $user->google2fa_recovery_codes = json_encode(collect(range(1, 8))->map(fn() => Str::random(10)));
        $user->save();
        $request->session()->forget('2fa_secret');
        return new UserResource($user);
    }

    public function disable2fa(Request $request)
    {
        $user = $request->user();
        $user->google2fa_secret = null;
        $user->google2fa_recovery_codes = null;
        $user->save();
        return new UserResource($user);
    }

    public function resendVerification(Request $request)
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 422);
        }
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!']);
    }
}
