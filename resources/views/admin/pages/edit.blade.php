@extends('admin.layouts.app')

@section('page-title', 'Edit Page')
@section('page-subtitle', 'Update page content')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-pencil"></i> Edit Page
  </div>
  <div class="card-body">
    <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $page->content) }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      @if($page->slug === 'ilorin-history')
      <div class="mb-3">
        <label for="metadata" class="form-label">Timeline metadata (JSON)</label>
        <textarea class="form-control @error('metadata') is-invalid @enderror" id="metadata" name="metadata" rows="18">{{ old('metadata', $page->metadata ? json_encode($page->metadata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
        <small class="form-text text-muted">Use the supplied seed structure: eyebrow, intro, stats, and timeline.</small>
        @error('metadata')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      @endif
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Page</button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
