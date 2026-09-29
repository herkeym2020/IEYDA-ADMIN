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
    var table = $('#team-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        order: [[2, 'asc']],
        columnDefs: [
            { orderable: false, targets: 0 }
        ]
    });
    // Filter by type
    $('#type-filter').on('change', function() {
        var val = $(this).val();
        if(val === '') {
            table.column(3).search('').draw();
        } else {
            table.column(3).search('^'+val+'$', true, false).draw();
        }
    });
    // Select all
    $('.select-all').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    // Bulk delete
    $('.bulk-delete-btn').on('click', function(){
        var ids = [];
        $('#team-table .row-checkbox:checked').each(function(){
            ids.push($(this).val());
        });
        if(ids.length === 0) return alert('No team members selected.');
        if(confirm('Are you sure you want to delete selected team members?')) {
            $('.bulk-delete-ids').val(ids.join(','));
            $('.bulk-delete-form').submit();
        }
    });
});
</script>
@endpush

@extends('admin.layouts.app')

@section('page-title', 'Team Members')
@section('page-subtitle', 'Manage team and staff')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-users"></i> All Team Members</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.team-members.bulkUploadForm') }}" class="btn btn-modern btn-modern-secondary">
                <i class="fas fa-upload"></i> Bulk Upload
            </a>
            <a href="{{ route('admin.team-members.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add Team Member
            </a>
        </div>
    </div>
    <div class="modern-card-body">
        @php
        $typeLabels = [
            'grand_patron' => 'Grand Patron',
            'board_of_trustees' => 'Board of Trustees',
            'executive_present' => 'Executive Committee (Present)',
            'executive_pioneering' => 'Executive Committee (Pioneering)',
            'staff' => 'Staff',
            'volunteer' => 'Volunteers',
        ];
        @endphp
        <div class="mb-4">
            <label for="type-filter" class="form-label">Filter by Team Type:</label>
            <select id="type-filter" class="form-control" style="max-width: 300px; display: inline-block;">
                <option value="">All Types</option>
                @foreach($typeLabels as $type => $label)
                    <option value="{{ $label }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="table-responsive">
            <form class="bulk-delete-form" action="{{ route('admin.team-members.bulkDelete') }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="ids" class="bulk-delete-ids">
            </form>
            <div class="mb-3">
                <button type="button" class="btn btn-modern btn-modern-danger bulk-delete-btn">
                    <i class="fas fa-trash"></i> Bulk Delete
                </button>
            </div>
            <table id="team-table" class="modern-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <!-- Order column removed -->
                        <th>Name</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($teamMembers as $member)
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $member->id }}"></td>
                        <!-- Order column removed -->
                        <td>
                            @if($member->image)
                                <img src="{{ Storage::url($member->image) }}" alt="{{ $member->name }}" height="40" width="40" class="rounded-circle me-2">
                            @endif
                            <strong>{{ $member->name }}</strong>
                        </td>
                        <td>{{ $typeLabels[$member->type] ?? ucfirst(str_replace('_', ' ', $member->type)) }}</td>
                        <td>{{ $member->position }}</td>
                        <td>
                            @if($member->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.team-members.edit', $member) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
