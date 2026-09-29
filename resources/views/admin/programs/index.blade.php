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
    var table = $('#programs-table').DataTable({
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
        if(ids.length === 0) return alert('No programs selected.');
        if(confirm('Are you sure you want to delete selected programs?')) {
            $('#bulk-delete-ids').val(ids.join(','));
            $('#bulk-delete-form').submit();
        }
    });
});
</script>
@endpush

@extends('admin.layouts.app')

@section('page-title', 'Programs')
@section('page-subtitle', 'Manage educational programs')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-graduation-cap"></i> All Programs</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.programs.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add Program
            </a>
            <button id="bulk-delete-btn" class="btn btn-modern btn-modern-danger">
                <i class="fas fa-trash"></i> Bulk Delete
            </button>
        </div>
    </div>
    <div class="modern-card-body">
        <form id="bulk-delete-form" action="{{ route('admin.programs.bulkDelete') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="ids" id="bulk-delete-ids">
        </form>
        <div class="table-responsive">
            <table id="programs-table" class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="select-all"></th>
                        <th style="width: 80px;">Order</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $program)
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" value="{{ $program->id }}"></td>
                            <td>{{ $program->order }}</td>
                            <td><strong>{{ $program->title }}</strong></td>
                            <td><span class="badge bg-info">{{ $program->category }}</span></td>
                            <td>{{ $program->duration ?? 'N/A' }}</td>
                            <td>
                                @if($program->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($program->status === 'upcoming')
                                    <span class="badge bg-warning">Upcoming</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No programs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Remove Laravel pagination for DataTables -->
    </div>
</div>
@endsection
