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
    var table = $('#testimonials-table').DataTable({
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
        if(ids.length === 0) return alert('No testimonials selected.');
        if(confirm('Are you sure you want to delete selected testimonials?')) {
            $('#bulk-delete-ids').val(ids.join(','));
            $('#bulk-delete-form').submit();
        }
    });
});
</script>
@endpush

@extends('admin.layouts.app')

@section('page-title', 'Testimonials')
@section('page-subtitle', 'Manage client testimonials')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-comment-dots"></i> All Testimonials</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add Testimonial
            </a>
            <button id="bulk-delete-btn" class="btn btn-modern btn-modern-danger">
                <i class="fas fa-trash"></i> Bulk Delete
            </button>
        </div>
    </div>
    <div class="modern-card-body">
        <form id="bulk-delete-form" action="{{ route('admin.testimonials.bulkDelete') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="ids" id="bulk-delete-ids">
        </form>
        <div class="table-responsive">
            <table id="testimonials-table" class="table table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Organization</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" value="{{ $testimonial->id }}"></td>
                            <td>
                                <strong>{{ $testimonial->name }}</strong>
                                @if($testimonial->is_featured)
                                    <span class="badge bg-warning text-dark ms-1">Featured</span>
                                @endif
                            </td>
                            <td>{{ $testimonial->position ?? 'N/A' }}</td>
                            <td>{{ $testimonial->organization ?? 'N/A' }}</td>
                            <td>
                                @if($testimonial->rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($testimonial->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="7" class="text-center text-muted py-4">No testimonials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
