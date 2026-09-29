@extends('admin.layouts.app')

@section('title', 'Qur\'an Competition Participants')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-trophy-fill text-primary mr-2"></i>
                Qur'an Competition Participants
            </h1>
            <p class="text-muted">Ilorin Children's Qur'an Recitation Competition — 5th August, Kwara State Banquet Hall</p>
        </div>
        <a href="{{ route('admin.quran-competition.export') }}" class="btn btn-success">
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
            <div class="small-box bg-success">
                <div class="inner"><h3>{{ $stats['approved'] }}</h3><p>Approved</p></div>
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-primary">
                <div class="inner"><h3>{{ $stats['shortlisted'] }}</h3><p>Shortlisted</p></div>
                <i class="bi bi-star-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-purple">
                <div class="inner"><h3>{{ $stats['finalists'] }}</h3><p>Finalists</p></div>
                <i class="bi bi-award-fill"></i>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
            <div class="small-box bg-danger">
                <div class="inner"><h3>{{ $stats['winners'] }}</h3><p>Winners</p></div>
                <i class="bi bi-crown-fill"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header"><h3 class="card-title"><i class="bi bi-funnel-fill mr-1"></i> Filter & Search</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.quran-competition.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search name, reg number, phone, madrasah..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        @foreach(['markaz'=>'Markaz','adaby'=>'Adaby','zumurah'=>'Zumurah','imam-agba'=>'Imam Agba','asily'=>'Asily'] as $val=>$label)
                            <option value="{{ $val }}" @selected(request('category')==$val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        @foreach(['pending'=>'Pending','under_review'=>'Under Review','verified'=>'Verified','approved'=>'Approved','shortlisted'=>'Shortlisted','finalist'=>'Finalist','winner'=>'Winner','rejected'=>'Rejected'] as $val=>$label)
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

    <!-- Participants Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-list-ul mr-1"></i> Participants ({{ $participants->total() }})</h3>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Reg. Number</th>
                            <th>Name</th>
                            <th class="d-none d-lg-table-cell">Photo</th>
                            <th class="d-none d-xl-table-cell">Age</th>
                            <th class="d-none d-xl-table-cell">Gender</th>
                            <th class="d-none d-lg-table-cell">Category</th>
                            <th class="d-none d-xl-table-cell">Guardian</th>
                            <th class="d-none d-xl-table-cell">Phone</th>
                            <th class="d-none d-md-table-cell">Status</th>
                            <th class="d-none d-xl-table-cell">Registered</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $p)
                        <tr>
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
                                @if(is_array($p->category))
                                    @foreach($p->category as $cat)
                                        <span class="badge badge-secondary mr-1 mb-1">{{ ucfirst(str_replace('-',' ',$cat)) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst(str_replace('-',' ',$p->category)) }}</span>
                                @endif
                            </td>
                            <td class="d-none d-xl-table-cell">{{ $p->guardian_name }}</td>
                            <td class="d-none d-xl-table-cell">{{ $p->phone }}</td>
                            <td class="d-none d-md-table-cell">
                                @php
                                    $statusColors = ['pending'=>'warning','under_review'=>'info','verified'=>'info','approved'=>'success','shortlisted'=>'primary','finalist'=>'purple','winner'=>'danger','rejected'=>'dark'];
                                    $color = $statusColors[$p->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ ucfirst(str_replace('_',' ',$p->status)) }}</span>
                            </td>
                            <td class="d-none d-xl-table-cell">{{ $p->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.quran-competition.show', $p->id) }}" class="btn btn-sm btn-info" title="View Full Profile">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="11" class="text-center text-muted py-4">No participants found.</td></tr>
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

<!-- ===== Profile Modal ===== -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="bi bi-person-circle mr-1"></i> Participant Profile
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" id="profileModalBody">
                <!-- Loading spinner -->
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted mt-3">Loading participant details...</p>
                </div>
                <!-- Profile content (populated by JS) -->
                <div id="modalContent" style="display:none;">
                    <!-- Photo & Header -->
                    <div class="text-center mb-4">
                        <div id="modalPhoto" class="mb-3"></div>
                        <h4 id="modalName" class="font-weight-bold mb-1"></h4>
                        <p id="modalRegNum" class="text-muted mb-2"></p>
                        <div id="modalStatus"></div>
                    </div>

                    <hr>

                    <!-- Personal Info -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person-fill mr-1"></i> Personal Information
                            </h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Date of Birth</small>
                            <span id="modalDob" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Age</small>
                            <span id="modalAge" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Gender</small>
                            <span id="modalGender" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Category</small>
                            <span id="modalCategory" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">School</small>
                            <span id="modalSchool" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">LGA / State</small>
                            <span id="modalLgaState" class="font-weight-bold"></span>
                        </div>
                        <div class="col-12 mb-2">
                            <small class="text-muted d-block">Address</small>
                            <span id="modalAddress" class="font-weight-bold"></span>
                        </div>
                    </div>

                    <hr>

                    <!-- Guardian Info -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-info border-bottom pb-2 mb-3">
                                <i class="bi bi-people-fill mr-1"></i> Parent / Guardian
                            </h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Guardian Name</small>
                            <span id="modalGuardian" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Relationship</small>
                            <span id="modalRelationship" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Phone</small>
                            <span id="modalPhone" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Email</small>
                            <span id="modalEmail" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Emergency Contact</small>
                            <span id="modalEmergency" class="font-weight-bold"></span>
                        </div>
                    </div>

                    <hr>

                    <!-- Islamic Education -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2 mb-3">
                                <i class="bi bi-book-fill mr-1"></i> Islamic Education
                            </h6>
                        </div>
                        <div class="col-md-12 mb-2">
                            <small class="text-muted d-block">Madrasah</small>
                            <span id="modalMadrasah" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Teacher Name</small>
                            <span id="modalTeacher" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Teacher Phone</small>
                            <span id="modalTeacherPhone" class="font-weight-bold"></span>
                        </div>
                    </div>

                    <hr>

                    <!-- Declarations -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-warning border-bottom pb-2 mb-3">
                                <i class="bi bi-shield-check-fill mr-1"></i> Declarations
                            </h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Age Confirmed</small>
                            <span id="modalDeclAge"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Info Accurate</small>
                            <span id="modalDeclAccurate"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Consent Obtained</small>
                            <span id="modalDeclConsent"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Rules Accepted</small>
                            <span id="modalDeclRules"></span>
                        </div>
                    </div>

                    <hr>

                    <!-- Meta -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-secondary border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle-fill mr-1"></i> Registration Meta
                            </h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Registered At</small>
                            <span id="modalCreatedAt" class="font-weight-bold"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Admin Notes</small>
                            <span id="modalAdminNotes" class="font-weight-bold"></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="bi bi-x-circle mr-1"></i> Close
                </button>
                <a href="#" id="modalFullProfileBtn" class="btn btn-info">
                    <i class="bi bi-arrow-up-right-square mr-1"></i> Open Full Profile
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openProfileModal(id) {
    // Show loading
    document.getElementById('modalLoading').style.display = 'block';
    document.getElementById('modalContent').style.display = 'none';

    // Show modal
    $('#profileModal').modal('show');

    // Fetch participant data
    fetch('{{ route('admin.quran-competition.index') }}/' + id + '/json', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(d => {
        // Photo
        if (d.photo_url) {
            document.getElementById('modalPhoto').innerHTML =
                '<img src="' + d.photo_url + '" alt="Photo" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;border:4px solid #2E7D32;">';
        } else {
            document.getElementById('modalPhoto').innerHTML =
                '<i class="bi bi-person-circle text-muted" style="font-size:100px;"></i>';
        }

        // Basic info
        document.getElementById('modalName').textContent = d.full_name;
        document.getElementById('modalRegNum').textContent = d.registration_number;
        document.getElementById('modalStatus').innerHTML =
            '<span class="badge badge-' + d.status_color + ' p-2" style="font-size:14px;">' + d.status + '</span>';

        // Personal
        document.getElementById('modalDob').textContent = d.date_of_birth;
        document.getElementById('modalAge').textContent = d.age + ' years';
        document.getElementById('modalGender').textContent = d.gender;
        document.getElementById('modalCategory').textContent = d.category;
        document.getElementById('modalSchool').textContent = d.school;
        document.getElementById('modalLgaState').textContent = d.lga + ', ' + d.state;
        document.getElementById('modalAddress').textContent = d.address;

        // Guardian
        document.getElementById('modalGuardian').textContent = d.guardian_name;
        document.getElementById('modalRelationship').textContent = d.relationship;
        document.getElementById('modalPhone').innerHTML = '<a href="tel:' + d.phone + '">' + d.phone + '</a>';
        document.getElementById('modalEmail').textContent = d.email;
        document.getElementById('modalEmergency').textContent = d.emergency_name + ' (' + d.emergency_phone + ')';

        // Islamic Education
        document.getElementById('modalMadrasah').textContent = d.madrasah;
        document.getElementById('modalTeacher').textContent = d.teacher_name;
        document.getElementById('modalTeacherPhone').textContent = d.teacher_phone;

        // Declarations
        document.getElementById('modalDeclAge').innerHTML = d.decl_age
            ? '<i class="bi bi-check-circle-fill text-success"></i> Yes'
            : '<i class="bi bi-x-circle-fill text-danger"></i> No';
        document.getElementById('modalDeclAccurate').innerHTML = d.decl_accurate
            ? '<i class="bi bi-check-circle-fill text-success"></i> Yes'
            : '<i class="bi bi-x-circle-fill text-danger"></i> No';
        document.getElementById('modalDeclConsent').innerHTML = d.decl_consent
            ? '<i class="bi bi-check-circle-fill text-success"></i> Yes'
            : '<i class="bi bi-x-circle-fill text-danger"></i> No';
        document.getElementById('modalDeclRules').innerHTML = d.decl_rules
            ? '<i class="bi bi-check-circle-fill text-success"></i> Yes'
            : '<i class="bi bi-x-circle-fill text-danger"></i> No';

        // Meta
        document.getElementById('modalCreatedAt').textContent = d.created_at;
        document.getElementById('modalAdminNotes').textContent = d.admin_notes;

        // Full profile link
        document.getElementById('modalFullProfileBtn').href = d.profile_url;

        // Hide loading, show content
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
    })
    .catch(err => {
        document.getElementById('modalLoading').innerHTML =
            '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Failed to load participant data. Please try again.</div>';
    });
}
</script>
@endpush
@endsection