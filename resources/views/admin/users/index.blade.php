@extends('admin.layouts.app')

@section('page-title', 'User Management')
@section('page-subtitle', 'Manage system users and their roles')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus mr-2"></i> Add New User
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Users</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar mr-2" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'super_admin')
                                        <span class="badge-modern badge-modern-danger"><i class="fas fa-crown"></i> Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge-modern badge-modern-warning"><i class="fas fa-user-shield"></i> Admin</span>
                                    @elseif($user->role === 'editor')
                                        <span class="badge-modern badge-modern-info"><i class="fas fa-edit"></i> Editor</span>
                                    @else
                                        <span class="badge-modern badge-modern-secondary"><i class="fas fa-eye"></i> Viewer</span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if(auth()->user()->isSuperAdmin() || (!$user->isSuperAdmin()))
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">Protected</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="card-footer clearfix">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
