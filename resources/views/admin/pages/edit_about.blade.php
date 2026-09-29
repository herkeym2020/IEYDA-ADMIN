@extends('admin.layouts.app')

@section('page-title', 'Edit About Page')
@section('page-subtitle', 'Update About page sections')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-pencil"></i> Edit About Page
  </div>
  <div class="card-body">
    <form action="{{ route('admin.pages.update', 'about') }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Intro Content</label>
        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4">{{ old('content', $page->content) }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="mission" class="form-label">Mission</label>
        <textarea class="form-control @error('mission') is-invalid @enderror" id="mission" name="mission" rows="3">{{ old('mission', $page->mission) }}</textarea>
        @error('mission')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="vision" class="form-label">Vision</label>
        <textarea class="form-control @error('vision') is-invalid @enderror" id="vision" name="vision" rows="3">{{ old('vision', $page->vision) }}</textarea>
        @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="values" class="form-label">Values</label>
        <textarea class="form-control @error('values') is-invalid @enderror" id="values" name="values" rows="3">{{ old('values', $page->values) }}</textarea>
        @error('values')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="history" class="form-label">History</label>
        <textarea class="form-control @error('history') is-invalid @enderror" id="history" name="history" rows="4">{{ old('history', $page->history) }}</textarea>
        @error('history')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update About Page</button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
