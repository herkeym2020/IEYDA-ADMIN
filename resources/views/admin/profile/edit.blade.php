@extends('admin.layouts.app')

@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Update your account information')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header p-0" style="background: linear-gradient(90deg, #4e73df 0%, #1cc88a 100%); height: 180px; position: relative;">
                <div style="position: absolute; bottom: -50px; left: 30px;">
                    <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128' }}" alt="Profile Photo" class="rounded-circle border border-white" style="width: 100px; height: 100px; object-fit: cover; border-width: 3px;">
                </div>
            </div>
            <div class="card-body pt-5 mt-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <p class="mb-1 text-muted">
                            <i class="bi bi-envelope"></i> {{ $user->email }}
                            @if (! $user->hasVerifiedEmail())
                                <span class="badge bg-warning text-dark ms-2">Unverified</span>
                                <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 ms-2 align-baseline">Resend Verification Email</button>
                                </form>
                            @else
                                <span class="badge bg-success ms-2">Verified</span>
                            @endif
                        </p>
                        @if($user->phone)
                            <p class="mb-1 text-muted"><i class="bi bi-telephone"></i> {{ $user->phone }}</p>
                        @endif
                        @if($user->address)
                            <p class="mb-1 text-muted"><i class="bi bi-geo-alt"></i> {{ $user->address }}</p>
                        @endif
                        @if($user->bio)
                            <p class="mb-1 text-muted"><i class="bi bi-person-lines-fill"></i> {{ $user->bio }}</p>
                        @endif
                    </div>
                    <div class="mt-3 mt-md-0 ms-md-4 d-flex flex-column gap-2">
                        <a href="{{ route('admin.profile.edit') }}?edit=1" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit Profile
                        </a>
                        <a href="{{ route('admin.profile.2fa') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-shield-lock"></i> Manage 2FA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
</div>
@endsection
