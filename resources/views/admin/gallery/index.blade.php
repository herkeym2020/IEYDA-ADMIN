@extends('admin.layouts.app')

@section('page-title', 'Gallery')
@section('page-subtitle', 'Manage photo gallery')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-images"></i> Photo Gallery</h3>
        <div class="page-header-actions">
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Upload Image
            </a>
        </div>
    </div>
    <div class="modern-card-body">
        <div class="row g-4">
            @forelse($galleries as $item)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-modern rounded-modern" style="transition: var(--transition);">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}" class="card-img-top" style="height: 200px; object-fit: cover; border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title font-weight-bold" style="color: var(--gray-900);">{{ $item->title }}</h6>
                            <p class="card-text small text-muted">
                                <i class="fas fa-tag"></i> {{ $item->category }}
                                @if($item->event_date)
                                    <br><i class="fas fa-calendar"></i> {{ $item->event_date->format('M d, Y') }}
                                @endif
                            </p>
                            @if($item->is_featured)
                                <span class="badge-modern badge-modern-warning"><i class="fas fa-star"></i> Featured</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-sm btn-modern-secondary flex-fill">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-modern-danger w-100">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    No gallery images found. Upload your first image!
                </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $galleries->links() }}
        </div>
    </div>
</div>
@endsection
