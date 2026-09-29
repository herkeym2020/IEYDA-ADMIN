@extends('admin.layouts.app')

@section('page-title', 'Hero Slides')
@section('page-subtitle', 'Manage homepage carousel slides')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-images"></i> All Hero Slides</span>
        <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Slide
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">Order</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>Image</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($heroSlides as $slide)
                        <tr>
                            <td>{{ $slide->order }}</td>
                            <td>{{ $slide->title }}</td>
                            <td>{{ Str::limit($slide->subtitle, 50) }}</td>
                            <td>
                                @if($slide->primary_image)
                                    <img src="{{ Storage::url($slide->primary_image) }}" alt="{{ $slide->title }}" height="40" class="rounded">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                @if($slide->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="6" class="text-center text-muted py-4">No hero slides found. Create your first one!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $heroSlides->links() }}
        </div>
    </div>
</div>
@endsection
