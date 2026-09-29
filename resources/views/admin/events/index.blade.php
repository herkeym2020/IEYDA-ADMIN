@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>
$(document).ready(function() {
    var table = $('#events-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: 0 }
        ]
    });
    // Select all
    $('#select-all').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    // Bulk delete
    $('#bulk-delete-btn').on('click', function(){
        var ids = [];
        $('.row-checkbox:checked').each(function(){
            ids.push($(this).val());
        });
        if(ids.length === 0) return alert('No events selected.');
        if(confirm('Are you sure you want to delete selected events?')) {
            $('#bulk-delete-ids').val(ids.join(','));
            $('#bulk-delete-form').submit();
        }
    });
});
</script>
@endpush

@extends('admin.layouts.app')

@section('page-title', 'Events')
@section('page-subtitle', 'Manage events and activities')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-calendar-alt"></i> All Events</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.events.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add Event
            </a>
            <button id="bulk-delete-btn" class="btn btn-modern btn-modern-danger">
                <i class="fas fa-trash"></i> Bulk Delete
            </button>
        </div>
    </div>
    <div class="modern-card-body">
        <form id="bulk-delete-form" action="{{ route('admin.events.bulkDelete') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="ids" id="bulk-delete-ids">
        </form>
        <div class="table-responsive">
            <table id="events-table" class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="select-all"></th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" value="{{ $event->id }}"></td>
                            <td>
                                <strong style="color: var(--gray-900);">{{ $event->title }}</strong>
                                @if($event->is_featured)
                                    <span class="badge-modern badge-modern-warning"><i class="fas fa-star"></i> Featured</span>
                                @endif
                            </td>
                            <td><span class="badge-modern badge-modern-info">{{ $event->category }}</span></td>
                            <td>{{ $event->event_date->format('M d, Y') }}</td>
                            <td>{{ Str::limit($event->location, 30) }}</td>
                            <td>
                                @if($event->status === 'upcoming')
                                    <span class="badge-modern badge-modern-success"><i class="fas fa-calendar-check"></i> Upcoming</span>
                                @elseif($event->status === 'completed')
                                    <span class="badge-modern badge-modern-secondary"><i class="fas fa-check-circle"></i> Completed</span>
                                @else
                                    <span class="badge-modern badge-modern-danger"><i class="fas fa-times-circle"></i> Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-modern-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-modern-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <h3>No Events</h3>
                                    <p>Get started by creating your first event</p>
                                    <a href="{{ route('admin.events.create') }}" class="btn btn-modern btn-modern-primary">
                                        <i class="fas fa-plus-circle"></i> Create Event
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Remove Laravel pagination for DataTables -->
    </div>
</div>
@endsection
