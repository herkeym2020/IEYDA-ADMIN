@extends('admin.layouts.app')

@section('title', 'Guest Registrations')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-0"><i class="bi bi-person-badge-fill text-primary mr-2"></i>Guest Registrations</h1>
                <p class="text-muted mb-0">Qur'an Championship 2026 — E-Invitations</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle mr-1"></i>{{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            @php
                $total = $guests->total();
                $confirmed = \App\Models\GuestRegistration::where('status','confirmed')->count();
                $pending = \App\Models\GuestRegistration::where('status','pending')->count();
                $attended = \App\Models\GuestRegistration::where('status','attended')->count();
                $cancelled = \App\Models\GuestRegistration::where('status','cancelled')->count();
            @endphp
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2"><div class="small-box bg-info"><div class="inner"><h3>{{ $total }}</h3><p>Total</p></div><div class="icon"><i class="bi bi-people-fill"></i></div></div></div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2"><div class="small-box bg-primary"><div class="inner"><h3>{{ $confirmed }}</h3><p>Confirmed</p></div><div class="icon"><i class="bi bi-check-circle-fill"></i></div></div></div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2"><div class="small-box bg-warning"><div class="inner"><h3>{{ $pending }}</h3><p>Pending</p></div><div class="icon"><i class="bi bi-hourglass-split"></i></div></div></div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2"><div class="small-box bg-success"><div class="inner"><h3>{{ $attended }}</h3><p>Attended</p></div><div class="icon"><i class="bi bi-person-check-fill"></i></div></div></div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2"><div class="small-box bg-danger"><div class="inner"><h3>{{ $cancelled }}</h3><p>Cancelled</p></div><div class="icon"><i class="bi bi-x-circle-fill"></i></div></div></div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                <div class="small-box bg-secondary">
                    <div class="inner"><h3><a href="{{ route('admin.dashboard') }}" class="text-white"><i class="bi bi-box-arrow-left"></i></a></h3><p>Back</p></div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary mb-3">
            <div class="card-header"><h3 class="card-title"><i class="bi bi-funnel-fill mr-1"></i>Filters</h3></div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name / email / phone / reg no..."></div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
                                <option value="confirmed" {{ request('status')==='confirmed'?'selected':'' }}>Confirmed</option>
                                <option value="attended" {{ request('status')==='attended'?'selected':'' }}>Attended</option>
                                <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2"><button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button></div>
                        <div class="col-md-2"><a href="{{ route('admin.guest-registrations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a></div>
                        @if($guests->total() > 0)
                            <div class="col-md-2 text-md-right">
                                <button type="button" class="btn btn-danger" onclick="if(confirm('Delete ALL guest registrations?')) document.getElementById('clear-all').submit();"><i class="bi bi-trash-fill"></i> Clear All</button>
                                <form id="clear-all" action="{{ route('admin.guest-registrations.destroy-all') }}" method="POST" style="display:none;">@csrf</form>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-info mb-3">
            <div class="card-header"><h3 class="card-title"><i class="bi bi-sliders mr-1"></i>Registration Controls</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.guest-registrations.update-settings') }}">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Registration Limit</label>
                            <input type="number" min="0" name="registration_limit" class="form-control" value="{{ $settings['limit'] }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">One registration per device</label>
                            <select name="device_restriction_enabled" class="form-control">
                                <option value="1" {{ $settings['device_restriction_enabled'] ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ !$settings['device_restriction_enabled'] ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-secondary mb-3">
            <div class="card-header"><h3 class="card-title"><i class="bi bi-download mr-1"></i>Export & Bulk Actions</h3></div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <a href="{{ route('admin.guest-registrations.export-csv') }}" class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
                        <a href="{{ route('admin.guest-registrations.export-pdf') }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
                    </div>
                    <div class="col-md-8">
                        <form method="POST" action="{{ route('admin.guest-registrations.bulk-action') }}" id="bulk-action-form">
                            @csrf
                            <input type="hidden" name="bulk_action" id="bulk-action-type" value="update">
                            <div class="input-group">
                                <select name="status" class="form-control">
                                    <option value="confirmed">Set status to Confirmed</option>
                                    <option value="pending">Set status to Pending</option>
                                    <option value="attended">Set status to Attended</option>
                                    <option value="cancelled">Set status to Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-primary" onclick="document.getElementById('bulk-action-type').value='update'">Apply Update</button>
                                <button type="submit" class="btn btn-danger" onclick="document.getElementById('bulk-action-type').value='delete'">Delete Selected</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="bi bi-table mr-1"></i>All Registrations ({{ $guests->total() }})</h3></div>
            <div class="card-body table-responsive p-0">
                @if($guests->count() > 0)
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th><input type="checkbox" id="select-all-guest" /></th>
                                <th>#</th>
                                <th>Registration No</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guests as $index => $guest)
                                <tr>
                                    <td><input type="checkbox" name="guest_ids[]" value="{{ $guest->id }}" form="bulk-action-form" /></td>
                                    <td>{{ $guests->firstItem() + $index }}</td>
                                    <td><span class="badge badge-success">{{ $guest->registration_number }}</span></td>
                                    <td><strong>{{ $guest->full_name }}</strong></td>
                                    <td>{{ $guest->email ?: '—' }}</td>
                                    <td>{{ $guest->phone ?: '—' }}</td>
                                    <td>@php $badge=['pending'=>'badge-warning','confirmed'=>'badge-info','attended'=>'badge-success','cancelled'=>'badge-danger'][$guest->status] ?? 'badge-secondary'; @endphp<span class="badge {{ $badge }}">{{ ucfirst($guest->status) }}</span></td>
                                    <td>{{ $guest->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.guest-registrations.show', $guest->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                        <form action="{{ route('admin.guest-registrations.destroy', $guest->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this registration?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center p-5"><i class="bi bi-person-x fa-3x text-muted mb-3 d-block"></i><p class="text-muted mb-0">No guest registrations found.</p></div>
                @endif
            </div>
            @if($guests->hasPages())
                <div class="card-footer">{{ $guests->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection