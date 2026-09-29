@extends('admin.layouts.app')

@section('page-title', 'Edit News Article')
@section('page-subtitle', 'Update news article')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-edit"></i> Edit News Article</h3>
    </div>
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="form-modern">
        @csrf
        @method('PUT')
        <div class="modern-card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $news->title) }}" required>
                        @error('title')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content', $news->content) }}</textarea>
                        @error('content')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $news->excerpt) }}</textarea>
                        <small class="form-text text-muted">Short summary for preview</small>
                        @error('excerpt')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image">Featured Image</label>
                        @if($news->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="img-fluid rounded">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current image</small>
                        @error('image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $news->category) }}" required>
                        @error('category')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $news->author) }}">
                        @error('author')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input class="custom-control-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_featured">Featured</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="scheduled_at">Schedule Publish (optional)</label>
                        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                        <small class="form-text text-muted">Set a future date/time to schedule or reschedule.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <button type="submit" name="draft_action" value="save_draft" class="btn btn-outline-secondary">
                <i class="fas fa-file-alt"></i> Save Draft
            </button>
            <button type="submit" name="draft_action" value="schedule" class="btn btn-outline-primary">
                <i class="fas fa-clock"></i> Schedule
            </button>
            <button type="submit" name="draft_action" value="publish" class="btn btn-primary">
                <i class="fas fa-save"></i> Publish Now
            </button>
        </div>
    </form>
</div>
@endsection
