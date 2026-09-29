@extends('admin.layouts.app')

@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information and permissions')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3><i class="fas fa-user-edit mr-2"></i> Edit User: {{ $user->name }}</h3>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-modern">
                @csrf
                @method('PUT')
                <div class="modern-card-body">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert-modern alert-modern-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Leave password fields empty to keep the current password</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Minimum 8 characters</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select name="role" 
                                id="role" 
                                class="form-control @error('role') is-invalid @enderror" 
                                required
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="">Select Role</option>
                            @if(auth()->user()->isSuperAdmin())
                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="viewer" {{ old('role', $user->role) === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <small class="form-text text-warning">You cannot change your own role</small>
                        @endif
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" 
                               name="address" 
                               id="address" 
                               class="form-control @error('address') is-invalid @enderror" 
                               value="{{ old('address', $user->address) }}">
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea name="bio" 
                                  id="bio" 
                                  class="form-control @error('bio') is-invalid @enderror" 
                                  rows="3">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-modern btn-modern-primary">
                        <i class="fas fa-save mr-2"></i> Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-modern btn-modern-secondary">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
