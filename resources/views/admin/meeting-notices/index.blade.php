@extends('admin.layouts.app')
@section('page-title', 'Meeting Notices')
@section('page-subtitle', 'Manage public meeting announcements and popups')
@section('content')
<div class="modern-card">
  <div class="modern-card-header"><h3><i class="fas fa-bullhorn"></i> Meeting Notices</h3><a href="{{ route('admin.meeting-notices.create') }}" class="btn btn-modern btn-modern-primary"><i class="fas fa-plus"></i> Add Notice</a></div>
  <div class="modern-card-body table-responsive">
    <table class="modern-table"><thead><tr><th>Title</th><th>Meeting date</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @forelse($notices as $notice)
      <tr><td><strong>{{ $notice->title }}</strong><br><small>{{ $notice->meeting_type }}</small></td><td>{{ $notice->starts_at?->format('M d, Y g:i A') }}</td><td>{{ $notice->location ?: '—' }}</td><td>@if($notice->is_active)<span class="badge-modern badge-modern-success">Active</span>@else<span class="badge-modern badge-modern-secondary">Off</span>@endif @if($notice->show_popup)<span class="badge-modern badge-modern-warning">Popup</span>@endif</td><td><a href="{{ route('admin.meeting-notices.edit', $notice) }}" class="btn btn-sm btn-modern-secondary"><i class="fas fa-edit"></i></a><form action="{{ route('admin.meeting-notices.destroy', $notice) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notice?')">@csrf @method('DELETE')<button class="btn btn-sm btn-modern-danger"><i class="fas fa-trash"></i></button></form></td></tr>
    @empty <tr><td colspan="5" class="text-center py-5">No meeting notices yet.</td></tr>@endforelse
    </tbody></table>
    {{ $notices->links() }}
  </div>
</div>
@endsection
