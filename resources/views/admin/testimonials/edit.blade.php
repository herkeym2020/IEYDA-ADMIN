@extends('admin.layouts.app')

@section('page-title', 'Edit Testimonial')
@section('page-subtitle', 'Update testimonial content')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-pencil"></i> Edit Testimonial
  </div>
  <div class="card-body">
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required>{{ old('content', $testimonial->content) }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="col-md-4">
          <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $testimonial->position) }}">
            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="organization" class="form-label">Organization</label>
            <input type="text" class="form-control @error('organization') is-invalid @enderror" id="organization" name="organization" value="{{ old('organization', $testimonial->organization) }}">
            @error('organization')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Avatar (optional)</label>
            @if($testimonial->image)
              <div class="mb-2"><img src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" class="img-fluid rounded"></div>
            @endif
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
            <small class="text-muted">Leave empty to keep current image</small>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating">
              <option value="">No rating</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ old('rating', $testimonial->rating)==$i ? 'selected' : '' }}>{{ $i }} star{{ $i>1?'s':'' }}</option>
              @endfor
            </select>
            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_featured">Featured</label>
            </div>
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Testimonial</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
