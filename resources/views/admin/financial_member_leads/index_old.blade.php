@extends('admin.layouts.app')

@section('title', 'Financial Member Leads')

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-md-8">
      <h2>Financial Member Leads</h2>
    </div>
    <div class="col-md-4 text-end">
      <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-primary">All</a>
      <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" class="btn btn-sm btn-outline-secondary">New</a>
      <a href="{{ route('admin.leads.index', ['status' => 'contacted']) }}" class="btn btn-sm btn-outline-success">Contacted</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($leads as $lead)
            <tr>
              <td>{{ $lead->name ?? '—' }}</td>
              <td>{{ $lead->email }}</td>
              <td>{{ $lead->phone ?? '—' }}</td>
              <td>
                <span class="badge bg-{{ $lead->status === 'contacted' ? 'success' : ($lead->status === 'ack_sent' ? 'info' : 'warning') }}">
                  {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                </span>
              </td>
              <td><small>{{ $lead->created_at->format('M d, Y H:i') }}</small></td>
              <td>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#emailModal{{ $lead->id }}">
                  Email
                </button>
                <form method="POST" action="{{ route('admin.leads.mark-contacted', $lead) }}" style="display:inline;">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-secondary">Mark Contacted</button>
                </form>
              </td>
            </tr>

            <!-- Email Modal with Template Selection -->
            <div class="modal fade" id="emailModal{{ $lead->id }}" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Send Email to {{ $lead->name ?? $lead->email }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="POST" action="{{ route('admin.leads.send-email', $lead) }}" id="emailForm{{ $lead->id }}">
                    @csrf
                    <div class="modal-body">
                      <!-- Message Type Selection -->
                      <div class="mb-3">
                        <label class="form-label">Message Type</label>
                        <div class="btn-group w-100" role="group">
                          <input type="radio" class="btn-check" name="message_type" id="auto{{ $lead->id }}" value="auto" checked>
                          <label class="btn btn-outline-primary" for="auto{{ $lead->id }}">Automated</label>

                          <input type="radio" class="btn-check" name="message_type" id="custom{{ $lead->id }}" value="custom">
                          <label class="btn btn-outline-primary" for="custom{{ $lead->id }}">Custom</label>
                        </div>
                      </div>

                      <!-- Automated Message Section -->
                      <div id="autoSection{{ $lead->id }}" class="auto-section">
                        <div class="alert alert-info">
                          <strong>Automated Message:</strong>
                          <p class="mb-0 mt-2">Thank you for your interest in becoming a financial member of IEYDA. We are excited about the opportunity to work with you. Our financial membership team will contact you shortly with the next steps and membership details.</p>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Subject</label>
                          <input type="text" name="subject_auto" class="form-control" value="Welcome to IEYDA - Financial Membership" readonly>
                        </div>
                      </div>

                      <!-- Custom Message Section (Hidden by default) -->
                      <div id="customSection{{ $lead->id }}" class="custom-section" style="display:none;">
                        <div class="mb-3">
                          <label class="form-label">Subject</label>
                          <input type="text" name="subject" class="form-control" placeholder="Enter email subject">
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Message</label>
                          <textarea name="body" class="form-control" rows="6" placeholder="Enter your custom message..."></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Send Email</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <script>
              document.getElementById('emailForm{{ $lead->id }}').addEventListener('change', function(e) {
                if (e.target.name === 'message_type') {
                  const isAuto = e.target.value === 'auto';
                  document.getElementById('autoSection{{ $lead->id }}').style.display = isAuto ? 'block' : 'none';
                  document.getElementById('customSection{{ $lead->id }}').style.display = isAuto ? 'none' : 'block';
                }
              });
            </script>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">No leads found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{ $leads->links() }}
</div>
@endsection
