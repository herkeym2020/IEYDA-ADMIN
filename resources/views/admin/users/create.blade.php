@extends('admin.layouts.app')

@section('page-title', 'Create User')
@section('page-subtitle', 'Add a new user to the system')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3><i class="fas fa-user-plus mr-2"></i> Add New User</h3>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="form-modern">
                @csrf
                <div class="modern-card-body">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
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
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Minimum 8 characters</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select name="role" 
                                id="role" 
                                class="form-control @error('role') is-invalid @enderror" 
                                required>
                            <option value="">Select Role</option>
                            @if(auth()->user()->isSuperAdmin())
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <strong>Super Admin:</strong> Full system access<br>
                            <strong>Admin:</strong> Manage users and all content<br>
                            <strong>Editor:</strong> Manage content only<br>
                            <strong>Viewer:</strong> Read-only access
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}">
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
                               value="{{ old('address') }}">
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea name="bio" 
                                  id="bio" 
                                  class="form-control @error('bio') is-invalid @enderror" 
                                  rows="3">{{ old('bio') }}</textarea>
                        @error('bio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-modern btn-modern-primary">
                        <i class="fas fa-save mr-2"></i> Create User
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
