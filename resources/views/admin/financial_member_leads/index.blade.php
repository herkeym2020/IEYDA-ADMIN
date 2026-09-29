@extends('admin.layouts.app')

@section('page-title', 'Financial Member Leads')
@section('page-subtitle', 'Manage and track financial membership inquiries')

@section('content')
<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-12">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addMemberModal">
            <i class="bi bi-person-plus"></i> Add Member
        </button>
        <a href="{{ route('admin.leads.export', ['status' => $status, 'tag' => $tag]) }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-download"></i> Export CSV
        </a>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel"></i> Filter
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-3">
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <h6 class="card-title text-muted small">Total Leads</h6>
                <h3 class="fw-bold text-primary mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <h6 class="card-title text-muted small">New</h6>
                <h3 class="fw-bold text-warning mb-0">{{ $stats['new'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <h6 class="card-title text-muted small">Contacted</h6>
                <h3 class="fw-bold text-info mb-0">{{ $stats['contacted'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <h6 class="card-title text-muted small">Qualified</h6>
                <h3 class="fw-bold text-success mb-0">{{ $stats['qualified'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <h6 class="card-title text-muted small">Converted</h6>
                <h3 class="fw-bold text-primary mb-0">{{ $stats['converted'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Active Filters -->
@if($search || $status || $tag)
    <div class="mb-3">
        <small class="text-muted">Active filters:</small>
        @if($search)
            <span class="badge bg-light text-dark">Search: {{ $search }}</span>
        @endif
        @if($status)
            <span class="badge bg-warning">Status: {{ ucfirst($status) }}</span>
        @endif
        @if($tag)
            <span class="badge bg-danger">Tag: {{ ucfirst($tag) }}</span>
        @endif
        <a href="{{ route('admin.leads.index') }}" class="text-decoration-none ms-2">Clear all</a>
    </div>
@endif

<!-- Leads Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header">
        <h5 class="card-title mb-0">All Members</h5>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Tier</th>
                    <th>Interactions</th>
                    <th>Created</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input lead-checkbox" value="{{ $lead->id }}">
                        </td>
                        <td>
                            <strong>{{ $lead->name ?? '—' }}</strong>
                        </td>
                        <td>
                            <a href="mailto:{{ $lead->email }}" class="text-decoration-none">{{ $lead->email }}</a>
                        </td>
                        <td>
                            <small class="text-muted">{{ $lead->phone ?? '—' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $lead->status_color ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($lead->tag)
                                <span class="badge bg-{{ $lead->tag_color ?? 'secondary' }}">{{ ucfirst($lead->tag) }}</span>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $lead->interaction_count ?? 0 }}</span>
                        </td>
                        <td>
                            <small>{{ $lead->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#viewModal{{ $lead->id }}" onclick="return false;">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#emailModal{{ $lead->id }}" onclick="return false;">
                                    <i class="bi bi-envelope"></i> Email
                                </a>
                                @if($lead->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}?text=Hello%20{{ urlencode($lead->name) }},%20We%20appreciate%20your%20support%20to%20IEYDA.%20Thank%20you!" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                @endif
                            </div>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal{{ $lead->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $lead->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel{{ $lead->id }}">Member Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">Name</h6>
                                                    <p class="fw-bold">{{ $lead->name }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">Email</h6>
                                                    <p><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">Phone</h6>
                                                    <p>{{ $lead->phone ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">Status</h6>
                                                    <p><span class="badge bg-{{ $lead->status_color ?? 'secondary' }}">{{ ucfirst($lead->status) }}</span></p>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">Tier</h6>
                                                    <p>{{ $lead->tag ? ucfirst($lead->tag) : '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted">Created</h6>
                                                    <p>{{ $lead->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            @if($lead->notes)
                                            <div class="mb-3">
                                                <h6 class="text-muted">Notes</h6>
                                                <p>{{ $lead->notes }}</p>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Modal -->
                            <div class="modal fade" id="emailModal{{ $lead->id }}" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel{{ $lead->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="emailModalLabel{{ $lead->id }}">Send Email to {{ $lead->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.leads.send-email', $lead) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">To:</label>
                                                    <input type="email" class="form-control" value="{{ $lead->email }}" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Subject</label>
                                                    <input type="text" name="subject" class="form-control" placeholder="Email subject" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Message</label>
                                                    <textarea name="body" class="form-control" rows="6" placeholder="Your message..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-send"></i> Send
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leads->hasPages())
        <div class="card-footer">
            {{ $leads->links() }}
        </div>
    @endif
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Add New Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.leads.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="Full name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="+234-801-234-5678">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Membership Tier</label>
                                <select name="tag" class="form-select">
                                    <option value="">Select Tier</option>
                                    <option value="bronze">🥉 Bronze</option>
                                    <option value="silver">🥈 Silver</option>
                                    <option value="gold">🥇 Gold</option>
                                    <option value="premium">⭐ Premium</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="converted">Converted ✓</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add any notes about this member..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('admin.leads.index') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, email, or phone" value="{{ $search }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="new" {{ $status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ $status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ $status === 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="converted" {{ $status === 'converted' ? 'selected' : '' }}>Converted</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tag</label>
                        <select name="tag" class="form-select">
                            <option value="">All Tags</option>
                            @foreach($tags as $t)
                                <option value="{{ $t }}" {{ $tag === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">Clear</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Monthly Outreach Modal -->
<div class="modal fade" id="monthlyOutreachModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-chat-dots"></i> Send Monthly Outreach Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.leads.send-monthly-outreach') }}" id="monthlyOutreachForm">
                @csrf
                <input type="hidden" name="lead_id" id="leadIdField">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Send appreciation or reminder messages to your members monthly
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Method</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="contact_method" id="methodEmail" value="email" checked>
                            <label class="btn btn-outline-primary" for="methodEmail">
                                <i class="bi bi-envelope"></i> Email
                            </label>

                            <input type="radio" class="btn-check" name="contact_method" id="methodWhatsapp" value="whatsapp">
                            <label class="btn btn-outline-success" for="methodWhatsapp">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </label>

                            <input type="radio" class="btn-check" name="contact_method" id="methodBoth" value="both">
                            <label class="btn btn-outline-info" for="methodBoth">
                                <i class="bi bi-chat"></i> Both
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Message Type</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="message_type" id="typeAppreciate" value="appreciate" checked>
                            <label class="btn btn-outline-warning" for="typeAppreciate">
                                <i class="bi bi-hand-thumbs-up"></i> Appreciation
                            </label>

                            <input type="radio" class="btn-check" name="message_type" id="typeReminder" value="reminder">
                            <label class="btn btn-outline-info" for="typeReminder">
                                <i class="bi bi-bell"></i> Reminder
                            </label>

                            <input type="radio" class="btn-check" name="message_type" id="typeCustom" value="custom">
                            <label class="btn btn-outline-secondary" for="typeCustom">
                                <i class="bi bi-pencil"></i> Custom
                            </label>
                        </div>
                    </div>

                    <!-- Appreciation Message Preview -->
                    <div id="appreciateSection" class="alert alert-light border">
                        <h6 class="fw-bold mb-2">💌 Appreciation Message</h6>
                        <p class="small mb-0"><strong>Subject:</strong> Thank You for Your Valued Support</p>
                        <p class="small mt-2 mb-0"><strong>Message:</strong></p>
                        <p class="small">We wanted to take a moment to appreciate your continued support and contribution to IEYDA. Your dedication makes a real difference. Thank you for being part of our mission!</p>
                    </div>

                    <!-- Reminder Message Preview -->
                    <div id="reminderSection" class="alert alert-light border" style="display:none;">
                        <h6 class="fw-bold mb-2">🔔 Reminder Message</h6>
                        <p class="small mb-0"><strong>Subject:</strong> Monthly Update - Your Membership Status</p>
                        <p class="small mt-2 mb-0"><strong>Message:</strong></p>
                        <p class="small">This is your monthly reminder about your membership with IEYDA. Your current status is active. If you have any questions or need to update your information, please reach out. We're here to support you!</p>
                    </div>

                    <!-- Custom Message Section -->
                    <div id="customSection" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Subject/Title</label>
                            <input type="text" name="custom_subject" class="form-control" placeholder="Enter subject">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="custom_message" class="form-control" rows="5" placeholder="Enter your custom message..."></textarea>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="log_as_activity" id="logActivity" checked>
                        <label class="form-check-label" for="logActivity">
                            Log this as activity/interaction
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Bulk selection
    const selectAllCheckbox = document.getElementById('selectAll');
    const leadCheckboxes = document.querySelectorAll('.lead-checkbox');
    const bulkActionsDiv = document.getElementById('bulkActions');

    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.lead-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActionsDiv.style.display = 'block';
            document.getElementById('selectedCount').textContent = checkedCount;
            const ids = Array.from(document.querySelectorAll('.lead-checkbox:checked')).map(cb => cb.value);
            document.getElementById('bulkIds').value = JSON.stringify(ids);
            document.getElementById('bulkIds2').value = JSON.stringify(ids);
        } else {
            bulkActionsDiv.style.display = 'none';
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            leadCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }

    leadCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    // Monthly Outreach Message Type Toggle
    document.querySelectorAll('input[name="message_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('appreciateSection').style.display = this.value === 'appreciate' ? 'block' : 'none';
            document.getElementById('reminderSection').style.display = this.value === 'reminder' ? 'block' : 'none';
            document.getElementById('customSection').style.display = this.value === 'custom' ? 'block' : 'none';
        });
    });
</script>

@endsection
