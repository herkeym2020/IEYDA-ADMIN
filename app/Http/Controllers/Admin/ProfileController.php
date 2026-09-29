<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Str;


class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = auth()->user();
        if ($request->has('edit')) {
            return view('admin.profile.edit_form', compact('user'));
        }
        return view('admin.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        $user->update($validated);
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password updated successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('admin.profile.change_password');
    }

    public function show2faForm(Request $request)
    {
        $user = $request->user();
        $google2fa_url = null;
        $secret = $user->google2fa_secret;
        if (!$secret) {
            $secret = Google2FA::generateSecretKey();
            $request->session()->put('2fa_secret', $secret);
            $google2fa_url = Google2FA::getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            );
        } else {
            $google2fa_url = Google2FA::getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            );
        }
        return view('admin.profile.2fa', compact('user', 'google2fa_url', 'secret'));
    }

    public function enable2fa(Request $request)
    {
        $user = $request->user();
        $secret = $request->input('secret') ?? $request->session()->get('2fa_secret');
        $valid = Google2FA::verifyKey($secret, $request->input('otp'));
        if ($valid) {
            $user->google2fa_secret = $secret;
            $user->google2fa_recovery_codes = json_encode(collect(range(1, 8))->map(fn() => Str::random(10)));
            $user->save();
            $request->session()->forget('2fa_secret');
            return redirect()->route('admin.profile.edit')->with('success', 'Two-factor authentication enabled.');
        }
        return back()->withErrors(['otp' => 'Invalid OTP code.']);
    }

    public function disable2fa(Request $request)
    {
        $user = $request->user();
        $user->google2fa_secret = null;
        $user->google2fa_recovery_codes = null;
        $user->save();
        return redirect()->route('admin.profile.edit')->with('success', 'Two-factor authentication disabled.');
    }
}

