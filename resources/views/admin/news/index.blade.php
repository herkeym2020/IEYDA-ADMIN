@extends('admin.layouts.app')

@section('page-title', 'News Articles')
@section('page-subtitle', 'Manage news and announcements')

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
    var table = $('#news-table').DataTable({
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
        if(ids.length === 0) return alert('No news articles selected.');
        if(confirm('Are you sure you want to delete selected news articles?')) {
            $('#bulk-delete-ids').val(ids.join(','));
            $('#bulk-delete-form').submit();
        }
    });
});
</script>
@endpush

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-newspaper"></i> All News Articles</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.news.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add News Article
            </a>
            <button id="bulk-delete-btn" class="btn btn-modern btn-modern-danger">
                <i class="fas fa-trash"></i> Bulk Delete
            </button>
        </div>
    </div>
    <div class="modern-card-body">
        <form id="bulk-delete-form" action="{{ route('admin.news.bulkDelete') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="ids" id="bulk-delete-ids">
        </form>
        <div class="table-responsive">
            <table id="news-table" class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="select-all"></th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $article)
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" value="{{ $article->id }}"></td>
                            <td>
                                <strong style="color: var(--gray-900);">{{ $article->title }}</strong>
                                @if($article->is_featured)
                                    <span class="badge-modern badge-modern-warning ml-1"><i class="fas fa-star"></i> Featured</span>
                                @endif
                            </td>
                            <td><span class="badge-modern badge-modern-info">{{ $article->category }}</span></td>
                            <td>
                                @if($article->image)
                                    <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="table-thumbnail">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                @if($article->is_published)
                                    <span class="badge-modern badge-modern-success"><i class="fas fa-check-circle"></i> Published</span>
                                @else
                                    <span class="badge-modern badge-modern-secondary"><i class="fas fa-file"></i> Draft</span>
                                @endif
                            </td>
                            <td>{{ $article->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-sm btn-modern-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <h3>No News Articles</h3>
                                    <p>Get started by creating your first news article</p>
                                    <a href="{{ route('admin.news.create') }}" class="btn btn-modern btn-modern-primary">
                                        <i class="fas fa-plus-circle"></i> Create News Article
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
