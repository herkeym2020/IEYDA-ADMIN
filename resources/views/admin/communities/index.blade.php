@extends('admin.layouts.app')

@section('page-title', 'Communities')
@section('page-subtitle', 'Manage community registrations')

@section('content')
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
    var table = $('#communities-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: 0 }
        ]
    });
    $('#select-all').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    $('#bulk-delete-btn').on('click', function(){
        var ids = [];
        $('.row-checkbox:checked').each(function(){
            ids.push($(this).val());
        });
        if(ids.length === 0) return alert('No communities selected.');
        if(confirm('Are you sure you want to delete selected communities?')) {
            $('#bulk-delete-ids').val(ids.join(','));
            $('#bulk-delete-form').submit();
        }
    });
});
</script>
@endpush

<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-network-wired"></i> Community Registrations</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.communities.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add Community
            </a>
            <button id="bulk-delete-btn" class="btn btn-modern btn-modern-danger">
                <i class="fas fa-trash"></i> Bulk Delete
            </button>
        </div>
    </div>
    <div class="modern-card-body">
        <form id="bulk-delete-form" action="{{ route('admin.communities.bulkDelete') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="ids" id="bulk-delete-ids">
        </form>
        <div class="table-responsive">
            <table id="communities-table" class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="select-all"></th>
                        <th>Name</th>
                        <th>LGA</th>
                        <th>Status</th>
                        <th>Contact</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($communities as $community)
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $community->id }}"></td>
                        <td><strong style="color: var(--gray-900);">{{ $community->name }}</strong></td>
                        <td>{{ $community->lga }}</td>
                        <td>
                            @if($community->status === 'approved')
                                <span class="badge-modern badge-modern-success"><i class="fas fa-check-circle"></i> Approved</span>
                            @elseif($community->status === 'pending')
                                <span class="badge-modern badge-modern-warning"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($community->status === 'declined')
                                <span class="badge-modern badge-modern-danger"><i class="fas fa-times-circle"></i> Declined</span>
                            @else
                                <span class="badge-modern badge-modern-secondary">{{ ucfirst($community->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <small style="color: var(--gray-600);">
                                <strong>{{ $community->contact_name }}</strong><br>
                                📧 {{ $community->contact_email }}<br>
                                📱 {{ $community->contact_phone }}
                            </small>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.communities.edit', $community) }}" class="btn btn-sm btn-modern-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($community->status === 'pending')
                                    <form action="{{ route('admin.communities.approve', $community) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-sm btn-modern-success">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.communities.decline', $community) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-sm btn-modern-danger">
                                            <i class="fas fa-times"></i> Decline
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.communities.destroy', $community) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No communities found. Communities will appear here once created.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
