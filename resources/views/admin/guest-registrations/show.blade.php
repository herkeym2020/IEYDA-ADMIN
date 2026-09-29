@extends('admin.layouts.app')

@section('title', 'Guest Registration Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-0"><i class="bi bi-person-badge text-primary mr-2"></i>Guest Registration Details</h1>
                <p class="text-muted mb-0">{{ $guest->registration_number }} — {{ $guest->full_name }}</p>
            </div>
            <div>
                <a href="{{ route('admin.guest-registrations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle mr-1"></i>{{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
        @endif

        <div class="row">
            <!-- Guest Information -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-person-circle mr-1 text-primary"></i>Guest Information</h3></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th style="width:180px;">Registration No</th><td><span class="badge badge-success">{{ $guest->registration_number }}</span></td></tr>
                            <tr><th>Full Name</th><td><strong>{{ $guest->full_name }}</strong></td></tr>
                            <tr><th>Email</th><td>{{ $guest->email ?: '—' }}</td></tr>
                            <tr><th>Phone</th><td>{{ $guest->phone ?: '—' }}</td></tr>
                            <tr><th>Status</th>
                                <td>@php $badge=['pending'=>'badge-warning','confirmed'=>'badge-info','attended'=>'badge-success','cancelled'=>'badge-danger'][$guest->status] ?? 'badge-secondary'; @endphp<span class="badge {{ $badge }}">{{ ucfirst($guest->status) }}</span></td>
                            </tr>
                            <tr><th>IP Address</th><td>{{ $guest->ip_address ?: '—' }}</td></tr>
                            <tr><th>Registered At</th><td>{{ $guest->created_at->format('M d, Y h:i A') }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-gear mr-1"></i>Actions</h3></div>
                    <div class="card-body">
                        <form action="{{ route('admin.guest-registrations.update-status', $guest->id) }}" method="POST" class="mb-3">
                            @csrf
                            <label class="form-label">Status</label>
                            <div class="input-group mb-3">
                                <select name="status" class="form-control">
                                    <option value="pending" {{ $guest->status==='pending'?'selected':'' }}>Pending</option>
                                    <option value="confirmed" {{ $guest->status==='confirmed'?'selected':'' }}>Confirmed</option>
                                    <option value="attended" {{ $guest->status==='attended'?'selected':'' }}>Attended</option>
                                    <option value="cancelled" {{ $guest->status==='cancelled'?'selected':'' }}>Cancelled</option>
                                </select>
                                <button class="btn btn-primary" type="submit"><i class="bi bi-check"></i></button>
                            </div>
                        </form>

                        <div class="d-grid gap-2">
                            @if($guest->email)
                                <a href="{{ asset('QuranRecitationInvitation.pdf') }}" target="_blank" class="btn btn-info"><i class="bi bi-file-earmark-pdf mr-1"></i>View Official E-Invitation</a>
                            @endif
                            <form action="{{ route('admin.guest-registrations.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Delete this registration?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"><i class="bi bi-trash mr-1"></i>Delete Registration</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection