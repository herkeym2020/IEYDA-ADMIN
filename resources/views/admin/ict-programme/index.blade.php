@extends('admin.layouts.app')

@section('title', 'ICT Programme Applicants')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-laptop-fill text-primary mr-2"></i>
                ICT Programme Applicants
            </h1>
            <p class="text-muted">11th Free ICT & Vocational Skills Acquisition Programme — August 3, 2026</p>
        </div>
        <a href="{{ route('admin.ict-programme.export') }}" class="btn btn-success">
            <i class="bi bi-download mr-1"></i> Export CSV
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-info">
                <div class="inner"><h3>{{ $stats['total'] }}</h3><p>Total</p></div>
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-warning">
                <div class="inner"><h3>{{ $stats['pending'] }}</h3><p>Pending</p></div>
                <i class="bi bi-clock-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-info">
                <div class="inner"><h3>{{ $stats['approved'] }}</h3><p>Approved</p></div>
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-success">
                <div class="inner"><h3>{{ $stats['admitted'] }}</h3><p>Admitted</p></div>
                <i class="bi bi-door-open-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-danger">
                <div class="inner"><h3>{{ $stats['rejected'] }}</h3><p>Rejected</p></div>
                <i class="bi bi-x-circle-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-primary">
                <div class="inner"><h3>{{ $stats['completed'] }}</h3><p>Completed</p></div>
                <i class="bi bi-award-fill"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header"><h3 class="card-title"><i class="bi bi-funnel-fill mr-1"></i> Filter & Search</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ict-programme.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search name, reg number, phone, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="course" class="form-control">
                        <option value="">All Courses</option>
                        @foreach(\App\Models\IctProgrammeApplicant::getIctCourses() as $val=>$label)
                            <option value="{{ $val }}" @selected(request('course')==$val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        @foreach(['pending'=>'Pending','approved'=>'Approved','admitted'=>'Admitted','rejected'=>'Rejected','completed'=>'Completed','graduated'=>'Graduated'] as $val=>$label)
                            <option value="{{ $val }}" @selected(request('status')==$val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-control">
                        <option value="">All Genders</option>
                        <option value="male" @selected(request('gender')=='male')>Male</option>
                        <option value="female" @selected(request('gender')=='female')>Female</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-search mr-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Toolbar -->
    <div class="card card-outline card-success mb-3" id="bulkActionsBar" style="display:none;">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-check2-square mr-1"></i> Bulk Actions <span id="selectedCount" class="badge badge-primary ml-1">0 selected</span></h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.ict-programme.bulk-update-status') }}" class="d-inline">
                @csrf
                <input type="hidden" name="ids" id="bulkIdsStatus">
                <select name="status" class="form-control d-inline-block" style="width:auto;" required>
                    <option value="">Select Status</option>
                    <option value="approved">Approve</option>
                    <option value="admitted">Admit</option>
                    <option value="rejected">Reject</option>
                    <option value="completed">Mark Completed</option>
                    <option value="graduated">Mark Graduated</option>
                </select>
                <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat mr-1"></i> Update Status</button>
            </form>
            <form method="POST" action="{{ route('admin.ict-programme.bulk-verify') }}" class="d-inline">
                @csrf
                <input type="hidden" name="ids" id="bulkIdsVerify">
                <button type="submit" class="btn btn-info"><i class="bi bi-patch-check mr-1"></i> Verify</button>
            </form>
            <button type="button" class="btn btn-warning" onclick="openBulkMessageModal()">
                <i class="bi bi-envelope-paper mr-1"></i> Send Message
            </button>
        </div>
    </div>

    <!-- Applicants Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-list-ul mr-1"></i> Applicants ({{ $participants->total() }})</h3>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                            <th>Reg. Number</th>
                            <th>Name</th>
                            <th class="d-none d-lg-table-cell">Photo</th>
                            <th class="d-none d-xl-table-cell">Age</th>
                            <th class="d-none d-xl-table-cell">Gender</th>
                            <th class="d-none d-lg-table-cell">ICT Courses</th>
                            <th class="d-none d-xl-table-cell">Phone</th>
                            <th class="d-none d-xl-table-cell">Email</th>
                            <th class="d-none d-md-table-cell">Status</th>
                            <th class="d-none d-xl-table-cell">Registered</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $p)
                        <tr>
                            <td><input type="checkbox" class="applicant-checkbox" value="{{ $p->id }}" onclick="updateBulkSelection()"></td>
                            <td><strong>{{ $p->registration_number }}</strong></td>
                            <td>
                                <a href="javascript:void(0)" onclick="openProfileModal({{ $p->id }})" class="text-primary font-weight-bold" style="cursor:pointer;text-decoration:none;">
                                    {{ $p->full_name }}
                                </a>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($p->photo_path)
                                    <img src="{{ asset('storage/' . $p->photo_path) }}" alt="Photo" class="img-circle" style="width:40px;height:40px;object-fit:cover;">
                                @else
                                    <i class="bi bi-person-circle text-muted" style="font-size:24px;"></i>
                                @endif
                            </td>
                            <td class="d-none d-xl-table-cell">{{ $p->age }}</td>
                            <td class="d-none d-xl-table-cell"><span class="badge badge-{{ $p->gender=='male'?'info':'danger' }}">{{ ucfirst($p->gender) }}</span></td>
                            <td class="d-none d-lg-table-cell">
                                @if(is_array($p->ict_courses))
                                    @foreach($p->ict_courses as $course)
                                        <span class="badge badge-secondary mr-1 mb-1">{{ ucfirst(str_replace('_',' ',$course)) }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="d-none d-xl-table-cell">{{ $p->phone }}</td>
                            <td class="d-none d-xl-table-cell">{{ $p->email ?: 'N/A' }}</td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge badge-{{ $p->status_color }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td class="d-none d-xl-table-cell">{{ $p->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.ict-programme.show', $p->id) }}" class="btn btn-sm btn-info" title="View Full Profile">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="11" class="text-center text-muted py-4">No applicants found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $participants->links() }}
        </div>
    </div>
</div>

<!-- ===== Bulk Message Modal ===== -->
<div class="modal fade" id="bulkMessageModal" tabindex="-1" role="dialog" aria-labelledby="bulkMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="bulkMessageModalLabel">
                    <i class="bi bi-envelope-paper mr-1"></i> Send Message to Selected Applicants
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.ict-programme.bulk-send-message') }}">
                @csrf
                <input type="hidden" name="ids" id="bulkIdsMessage">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Enter your message here..."></textarea>
                        <small class="text-muted">This message will be sent via email and WhatsApp to all selected applicants.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-send mr-1"></i> Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== Profile Modal ===== -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="bi bi-laptop-fill mr-1"></i> Applicant Profile
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="profileModalBody">
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted mt-3">Loading applicant details...</p>
                </div>
                <div id="modalContent" style="display:none;">
                    <div class="text-center mb-4">
                        <div id="modalPhoto" class="mb-3"></div>
                        <h4 id="modalName" class="font-weight-bold mb-1"></h4>
                        <p id="modalRegNum" class="text-muted mb-2"></p>
                        <div id="modalStatus"></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-person-fill mr-1"></i> Personal Information</h6>
                        </div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Date of Birth</small><span id="modalDob" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Age</small><span id="modalAge" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Gender</small><span id="modalGender" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Phone</small><span id="modalPhone" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Email</small><span id="modalEmail" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Education Level</small><span id="modalEducation" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Occupation</small><span id="modalOccupation" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">LGA / State</small><span id="modalLgaState" class="font-weight-bold"></span></div>
                        <div class="col-12 mb-2"><small class="text-muted d-block">Address</small><span id="modalAddress" class="font-weight-bold"></span></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3"><i class="bi bi-laptop-fill mr-1"></i> Programme Selection</h6>
                        </div>
                        <div class="col-12 mb-2"><small class="text-muted d-block">ICT Courses</small><span id="modalIctCourses" class="font-weight-bold"></span></div>
                        <div class="col-12 mb-2"><small class="text-muted d-block">Vocational Interests</small><span id="modalVocational" class="font-weight-bold"></span></div>
                        <div class="col-12 mb-2"><small class="text-muted d-block">Expectations</small><span id="modalExpectations" class="font-weight-bold"></span></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-info border-bottom pb-2 mb-3"><i class="bi bi-people-fill mr-1"></i> Guardian Information</h6>
                        </div>
                        <div class="col-md-4 mb-2"><small class="text-muted d-block">Guardian Name</small><span id="modalGuardian" class="font-weight-bold"></span></div>
                        <div class="col-md-4 mb-2"><small class="text-muted d-block">Phone</small><span id="modalGuardianPhone" class="font-weight-bold"></span></div>
                        <div class="col-md-4 mb-2"><small class="text-muted d-block">Relationship</small><span id="modalGuardianRel" class="font-weight-bold"></span></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="bi bi-info-circle-fill mr-1"></i> Registration Meta</h6>
                        </div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Registered At</small><span id="modalCreatedAt" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Email Sent</small><span id="modalEmailSent" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">WhatsApp Sent</small><span id="modalWhatsappSent" class="font-weight-bold"></span></div>
                        <div class="col-md-6 mb-2"><small class="text-muted d-block">Admin Notes</small><span id="modalAdminNotes" class="font-weight-bold"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="bi bi-x-circle mr-1"></i> Close</button>
                <a href="#" id="modalFullProfileBtn" class="btn btn-info"><i class="bi bi-arrow-up-right-square mr-1"></i> Open Full Profile</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.applicant-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateBulkSelection();
}

function updateBulkSelection() {
    const selected = Array.from(document.querySelectorAll('.applicant-checkbox:checked')).map(cb => cb.value);
    const bar = document.getElementById('bulkActionsBar');
    const count = document.getElementById('selectedCount');
    count.textContent = selected.length + ' selected';
    bar.style.display = selected.length > 0 ? 'block' : 'none';
    document.getElementById('bulkIdsStatus').value = JSON.stringify(selected);
    document.getElementById('bulkIdsVerify').value = JSON.stringify(selected);
    document.getElementById('bulkIdsMessage').value = JSON.stringify(selected);
}

function openBulkMessageModal() {
    const selected = Array.from(document.querySelectorAll('.applicant-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Please select at least one applicant.');
        return;
    }
    $('#bulkMessageModal').modal('show');
}

function openProfileModal(id) {
    document.getElementById('modalLoading').style.display = 'block';
    document.getElementById('modalContent').style.display = 'none';
    $('#profileModal').modal('show');
    fetch('{{ route('admin.ict-programme.index') }}/' + id + '/json', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(d => {
        if (d.photo_url) {
            document.getElementById('modalPhoto').innerHTML = '<img src="' + d.photo_url + '" alt="Photo" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;border:4px solid #2E7D32;">';
        } else {
            document.getElementById('modalPhoto').innerHTML = '<i class="bi bi-person-circle text-muted" style="font-size:100px;"></i>';
        }
        document.getElementById('modalName').textContent = d.full_name;
        document.getElementById('modalRegNum').textContent = d.registration_number;
        document.getElementById('modalStatus').innerHTML = '<span class="badge badge-' + d.status_color + ' p-2" style="font-size:14px;">' + d.status + '</span>';
        document.getElementById('modalDob').textContent = d.date_of_birth;
        document.getElementById('modalAge').textContent = d.age + ' years';
        document.getElementById('modalGender').textContent = d.gender;
        document.getElementById('modalPhone').innerHTML = '<a href="tel:' + d.phone + '">' + d.phone + '</a>';
        document.getElementById('modalEmail').textContent = d.email;
        document.getElementById('modalEducation').textContent = d.education_level;
        document.getElementById('modalOccupation').textContent = d.occupation;
        document.getElementById('modalLgaState').textContent = d.lga + ', ' + d.state;
        document.getElementById('modalAddress').textContent = d.address;
        document.getElementById('modalIctCourses').textContent = d.ict_courses;
        document.getElementById('modalVocational').textContent = d.vocational_interests;
        document.getElementById('modalExpectations').textContent = d.expectations;
        document.getElementById('modalGuardian').textContent = d.guardian_name;
        document.getElementById('modalGuardianPhone').textContent = d.guardian_phone;
        document.getElementById('modalGuardianRel').textContent = d.guardian_relationship;
        document.getElementById('modalCreatedAt').textContent = d.created_at;
        document.getElementById('modalEmailSent').textContent = d.email_sent;
        document.getElementById('modalWhatsappSent').textContent = d.whatsapp_sent;
        document.getElementById('modalAdminNotes').textContent = d.admin_notes;
        document.getElementById('modalFullProfileBtn').href = d.profile_url;
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
    })
    .catch(err => {
        document.getElementById('modalLoading').innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Failed to load applicant data.</div>';
    });
}
</script>
@endpush
@endsection