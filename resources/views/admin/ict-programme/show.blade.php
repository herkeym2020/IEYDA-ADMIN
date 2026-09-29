@extends('admin.layouts.app')

@section('title', 'ICT Applicant: ' . $participant->full_name)

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.ict-programme.index') }}" class="btn btn-default mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Back to List
    </a>

    <div class="row">
        <!-- Left Column: Profile -->
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-person-fill mr-1"></i> Applicant Profile</h3>
                </div>
                <div class="card-body">
                    <!-- Photo & Header -->
                    <div class="text-center mb-4">
                        @if($participant->photo_path)
                            <img src="{{ asset('storage/' . $participant->photo_path) }}" alt="Photo" class="rounded-circle mb-3" style="width:150px;height:150px;object-fit:cover;border:4px solid #2E7D32;">
                        @else
                            <i class="bi bi-person-circle text-muted" style="font-size:120px;"></i>
                        @endif
                        <h3 class="font-weight-bold">{{ $participant->full_name }}</h3>
                        <p class="text-muted">{{ $participant->registration_number }}</p>
                        <span class="badge badge-{{ $participant->status_color }} p-2" style="font-size:14px;">{{ ucfirst($participant->status) }}</span>
                    </div>

                    <hr>

                    <!-- Personal Info -->
                    <h5 class="text-primary mb-3"><i class="bi bi-person-fill mr-1"></i> Personal Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Date of Birth</small><strong>{{ $participant->date_of_birth->format('M d, Y') }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Age</small><strong>{{ $participant->age }} years</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Gender</small><strong>{{ ucfirst($participant->gender) }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Phone</small><strong><a href="tel:{{ $participant->phone }}">{{ $participant->phone }}</a></strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Email</small><strong>{{ $participant->email ?: 'N/A' }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Education Level</small><strong>{{ $participant->education_level ?: 'N/A' }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Occupation</small><strong>{{ $participant->occupation ?: 'N/A' }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">LGA / State</small><strong>{{ $participant->lga ?: 'N/A' }}, {{ $participant->state }}</strong></div>
                        <div class="col-12 mb-2"><small class="text-muted d-block">Address</small><strong>{{ $participant->address }}</strong></div>
                    </div>

                    <hr>

                    <!-- Programme Selection -->
                    <h5 class="text-success mb-3"><i class="bi bi-laptop-fill mr-1"></i> Programme Selection</h5>
                    <div class="mb-4">
                        <small class="text-muted d-block">ICT Courses Selected:</small>
                        <div class="mt-1">
                            @if(is_array($participant->ict_courses))
                                @foreach($participant->ict_courses as $course)
                                    <span class="badge badge-success mr-1 mb-1 p-2">{{ \App\Models\IctProgrammeApplicant::getIctCourses()[$course] ?? ucfirst(str_replace('_',' ',$course)) }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <small class="text-muted d-block">Vocational Interests:</small>
                        <div class="mt-1">
                            @if(is_array($participant->vocational_interests) && count($participant->vocational_interests) > 0)
                                @foreach($participant->vocational_interests as $voc)
                                    <span class="badge badge-info mr-1 mb-1 p-2">{{ \App\Models\IctProgrammeApplicant::getVocationalSessions()[$voc] ?? ucfirst(str_replace('_',' ',$voc)) }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">None selected</span>
                            @endif
                        </div>
                    </div>
                    @if($participant->expectations)
                    <div class="mb-4">
                        <small class="text-muted d-block">Expectations:</small>
                        <p class="mt-1">{{ $participant->expectations }}</p>
                    </div>
                    @endif

                    <hr>

                    <!-- Guardian Info -->
                    <h5 class="text-info mb-3"><i class="bi bi-people-fill mr-1"></i> Guardian Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-2"><small class="text-muted d-block">Guardian Name</small><strong>{{ $participant->guardian_name ?: 'N/A' }}</strong></div>
                        <div class="col-md-4 mb-2"><small class="text-muted d-block">Phone</small><strong>{{ $participant->guardian_phone ?: 'N/A' }}</strong></div>
                        <div class="col-md-4 mb-2"><small class="text-muted d-block">Relationship</small><strong>{{ $participant->guardian_relationship ?: 'N/A' }}</strong></div>
                    </div>

                    <hr>

                    <!-- Meta -->
                    <h5 class="text-secondary mb-3"><i class="bi bi-info-circle-fill mr-1"></i> Registration Meta</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Registered At</small><strong>{{ $participant->created_at->format('M d, Y g:i A') }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Email Sent</small><strong>{{ $participant->email_sent_at ? 'Yes (' . $participant->email_sent_at->format('M d, Y g:i A') . ')' : 'No' }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">WhatsApp Sent</small><strong>{{ $participant->whatsapp_sent_at ? 'Yes (' . $participant->whatsapp_sent_at->format('M d, Y g:i A') . ')' : 'No' }}</strong></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">IP Address</small><strong>{{ $participant->ip_address ?: 'N/A' }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Status Management -->
        <div class="col-md-4">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-gear-fill mr-1"></i> Status Management</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ict-programme.update-status', $participant->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Current Status</label>
                            <p><span class="badge badge-{{ $participant->status_color }} p-2" style="font-size:14px;">{{ ucfirst($participant->status) }}</span></p>
                        </div>
                        <div class="form-group">
                            <label for="status">Update Status</label>
                            <select name="status" id="status" class="form-control">
                                @foreach(['pending'=>'Pending','approved'=>'Approved','admitted'=>'Admitted','rejected'=>'Rejected','completed'=>'Completed','graduated'=>'Graduated'] as $val=>$label)
                                    <option value="{{ $val }}" @selected($participant->status==$val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="admin_notes">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Add notes about this applicant...">{{ $participant->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="bi bi-check-circle mr-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            @if($participant->admitted_at)
            <div class="card card-outline card-success mt-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-door-open-fill mr-1"></i> Admission Info</h3>
                </div>
                <div class="card-body">
                    <p><strong>Admitted At:</strong> {{ $participant->admitted_at->format('M d, Y g:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection