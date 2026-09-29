@extends('admin.layouts.app')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Two-Factor Authentication (2FA)</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($user->google2fa_secret)
                        <div class="mb-3">
                            <span class="badge bg-success">2FA is enabled on your account.</span>
                        </div>
                        <form method="POST" action="{{ route('admin.profile.2fa.disable') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Disable 2FA</button>
                        </form>
                        <hr>
                        <h6>Recovery Codes</h6>
                        <ul>
                            @foreach (json_decode($user->google2fa_recovery_codes ?? '[]') as $code)
                                <li><code>{{ $code }}</code></li>
                            @endforeach
                        </ul>
                    @else
                        <div class="mb-3">
                            <span class="badge bg-warning text-dark">2FA is not enabled.</span>
                        </div>
                        <p>Scan the QR code below with your authenticator app (Google Authenticator, Authy, etc.), then enter the generated code to enable 2FA.</p>
                        <div class="mb-3 text-center">
                            @if ($google2fa_url)
                                @if (Str::startsWith($google2fa_url, '<svg'))
                                    {!! $google2fa_url !!}
                                @else
                                    <img src="{{ $google2fa_url }}" alt="2FA QR Code">
                                @endif
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.profile.2fa.enable') }}">
                            @csrf
                            <input type="hidden" name="secret" value="{{ $secret }}">
                            <div class="mb-3">
                                <label for="otp" class="form-label">Authenticator Code</label>
                                <input type="text" name="otp" id="otp" class="form-control @error('otp') is-invalid @enderror" required autocomplete="off">
                                @error('otp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Enable 2FA</button>
                        </form>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-link">Back to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
