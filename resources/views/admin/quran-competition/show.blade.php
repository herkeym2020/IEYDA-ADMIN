@extends('admin.layouts.app')

@section('title', 'Participant Profile — ' . $participant->full_name)

@section('content')
<div class="container-fluid">
    <!-- Back button -->
    <a href="{{ route('admin.quran-competition.index') }}" class="btn btn-default mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Back to Participants
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Left: Photo & Status -->
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-body text-center">
                    @if($participant->photo_path)
                        <img src="{{ asset('storage/' . $participant->photo_path) }}" alt="Photo" class="img-fluid rounded-circle mb-3" style="width:180px;height:180px;object-fit:cover;border:4px solid #2E7D32;">
                    @else
                        <i class="bi bi-person-circle text-muted" style="font-size:120px;"></i>
                    @endif
                    <h3 class="mb-1">{{ $participant->full_name }}</h3>
                    <p class="text-muted mb-2">{{ $participant->registration_number }}</p>
                    <div class="mb-3">
                        @php
                            $statusColors = ['pending'=>'warning','under_review'=>'info','verified'=>'info','approved'=>'success','shortlisted'=>'primary','finalist'=>'purple','winner'=>'danger','rejected'=>'dark'];
                            $color = $statusColors[$participant->status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $color }} p-2" style="font-size:14px;">Status: {{ ucfirst(str_replace('_',' ',$participant->status)) }}</span>
                    </div>
                    <div class="text-muted">
                        <p><i class="bi bi-calendar mr-1"></i> Age: <strong>{{ $participant->age }} years</strong></p>
                        <p><i class="bi bi-gender-ambiguous mr-1"></i> Gender: <strong>{{ ucfirst($participant->gender) }}</strong></p>
                        <p><i class="bi bi-trophy mr-1"></i> Categories:
                            @if(is_array($participant->category))
                                @foreach($participant->category as $cat)
                                    <span class="badge badge-secondary mr-1">{{ ucfirst(str_replace('-',' ',$cat)) }}</span>
                                @endforeach
                            @else
                                <strong>{{ ucfirst(str_replace('-',' ',$participant->category)) }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Update Form -->
            <div class="card card-outline card-success mt-3">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-gear-fill mr-1"></i> Update Status</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.quran-competition.update-status', $participant->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                @foreach(['pending'=>'Pending','under_review'=>'Under Review','verified'=>'Verified','approved'=>'Approved','shortlisted'=>'Shortlisted','finalist'=>'Finalist','winner'=>'Winner','rejected'=>'Rejected'] as $val=>$label)
                                    <option value="{{ $val }}" @selected($participant->status==$val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add notes about this participant...">{{ $participant->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><i class="bi bi-save mr-1"></i> Update</button>
                    </form>
                </div>
            </div>

            <!-- Contact Participant Form -->
            <div class="card card-outline card-info mt-3">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-envelope-fill mr-1"></i> Contact Participant</h3></div>
                <div class="card-body">
                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($participant->email)
                        <form action="{{ route('admin.quran-competition.send-email', $participant->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>To</label>
                                <input type="text" class="form-control" value="{{ $participant->email }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="e.g. Qur'an Competition Update" required>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Type your message to the guardian..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-info btn-block"><i class="bi bi-send-fill mr-1"></i> Send Email</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
                            This participant has no email address on file.<br>
                            You can reach the guardian via phone: <a href="tel:{{ $participant->phone }}"><strong>{{ $participant->phone }}</strong></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Details -->
        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card card-outline card-primary mb-3">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-person-fill mr-1"></i> Personal Information</h3></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="180">Full Name:</th><td>{{ $participant->full_name }}</td></tr>
                        <tr><th>Date of Birth:</th><td>{{ $participant->date_of_birth->format('F d, Y') }} ({{ $participant->age }} years)</td></tr>
                        <tr><th>Gender:</th><td>{{ ucfirst($participant->gender) }}</td></tr>
                        <tr><th>School:</th><td>{{ $participant->school }}</td></tr>
                        <tr><th>LGA:</th><td>{{ $participant->lga }}</td></tr>
                        <tr><th>State:</th><td>{{ $participant->state }}</td></tr>
                        <tr><th>Address:</th><td>{{ $participant->address }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Parent / Guardian -->
            <div class="card card-outline card-info mb-3">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-people-fill mr-1"></i> Parent / Guardian Information</h3></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="180">Guardian Name:</th><td>{{ $participant->guardian_name }}</td></tr>
                        <tr><th>Relationship:</th><td>{{ ucfirst($participant->relationship) }}</td></tr>
                        <tr><th>Phone:</th><td><a href="tel:{{ $participant->phone }}">{{ $participant->phone }}</a></td></tr>
                        <tr><th>Email:</th><td>{{ $participant->email ?: '—' }}</td></tr>
                        <tr><th>Emergency Contact:</th><td>{{ $participant->emergency_name }} ({{ $participant->emergency_phone }})</td></tr>
                    </table>
                </div>
            </div>

            <!-- Islamic Education -->
            <div class="card card-outline card-success mb-3">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-book-fill mr-1"></i> Islamic Education Information</h3></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="180">Madrasah:</th><td>{{ $participant->madrasah }}</td></tr>
                        <tr><th>Teacher Name:</th><td>{{ $participant->teacher_name }}</td></tr>
                        <tr><th>Teacher Phone:</th><td>{{ $participant->teacher_phone ?: '—' }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Competition & Declaration -->
            <div class="card card-outline card-warning mb-3">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-trophy-fill mr-1"></i> Competition & Declaration</h3></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="180">Categories:</th>
                            <td>
                                @if(is_array($participant->category))
                                    @foreach($participant->category as $cat)
                                        <span class="badge badge-secondary mr-1 mb-1">{{ ucfirst(str_replace('-',' ',$cat)) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-secondary p-2">{{ ucfirst(str_replace('-',' ',$participant->category)) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Age Confirmed:</th><td>{!! $participant->decl_age ? '<i class="bi bi-check-circle-fill text-success"></i> Yes' : '<i class="bi bi-x-circle-fill text-danger"></i> No' !!}</td></tr>
                        <tr><th>Info Accurate:</th><td>{!! $participant->decl_accurate ? '<i class="bi bi-check-circle-fill text-success"></i> Yes' : '<i class="bi bi-x-circle-fill text-danger"></i> No' !!}</td></tr>
                        <tr><th>Consent Obtained:</th><td>{!! $participant->decl_consent ? '<i class="bi bi-check-circle-fill text-success"></i> Yes' : '<i class="bi bi-x-circle-fill text-danger"></i> No' !!}</td></tr>
                        <tr><th>Rules Accepted:</th><td>{!! $participant->decl_rules ? '<i class="bi bi-check-circle-fill text-success"></i> Yes' : '<i class="bi bi-x-circle-fill text-danger"></i> No' !!}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Meta -->
            <div class="card card-outline card-secondary">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-info-circle-fill mr-1"></i> Registration Meta</h3></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="180">Registration Number:</th><td><strong>{{ $participant->registration_number }}</strong></td></tr>
                        <tr><th>Registered At:</th><td>{{ $participant->created_at->format('F d, Y \a\t h:i A') }}</td></tr>
                        <tr><th>IP Address:</th><td>{{ $participant->ip_address ?: '—' }}</td></tr>
                        <tr><th>Admin Notes:</th><td>{{ $participant->admin_notes ?: '—' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection