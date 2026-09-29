@extends('admin.layouts.app')

@section('page-title', 'Edit Hero Slide')
@section('page-subtitle', 'Update carousel slide')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil"></i> Edit Hero Slide
    </div>
    <div class="card-body">
        <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $heroSlide->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="badge" class="form-label">Badge <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('badge') is-invalid @enderror" id="badge" name="badge" value="{{ old('badge', $heroSlide->badge) }}" required>
                        @error('badge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="motto" class="form-label">Motto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('motto') is-invalid @enderror" id="motto" name="motto" value="{{ old('motto', $heroSlide->motto) }}" required>
                        @error('motto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="yoruba_text" class="form-label">Yoruba Text <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('yoruba_text') is-invalid @enderror" id="yoruba_text" name="yoruba_text" value="{{ old('yoruba_text', $heroSlide->yoruba_text) }}" required>
                        @error('yoruba_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $heroSlide->subtitle) }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $heroSlide->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="button_text" class="form-label">Button Text</label>
                        <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $heroSlide->button_text) }}">
                        @error('button_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CTA Buttons <span class="text-danger">*</span></label>
                        <div id="cta-repeater">
                            @php $oldCtas = old('ctas', $heroSlide->ctas ?? [[ 'text' => '', 'link' => '' ]]); @endphp
                            @foreach($oldCtas as $i => $cta)
                            <div class="row g-2 mb-2 cta-row align-items-end">
                                <div class="col-5">
                                    <input type="text" name="ctas[{{ $i }}][text]" class="form-control @error('ctas.'.$i.'.text') is-invalid @enderror" placeholder="Button Text" value="{{ $cta['text'] ?? '' }}" required>
                                    @error('ctas.'.$i.'.text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-5">
                                    <input type="text" name="ctas[{{ $i }}][link]" class="form-control @error('ctas.'.$i.'.link') is-invalid @enderror" placeholder="Button Link" value="{{ $cta['link'] ?? '' }}" required>
                                    @error('ctas.'.$i.'.link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-2 d-flex gap-1">
                                    <button type="button" class="btn btn-success btn-sm add-cta"><i class="bi bi-plus"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm remove-cta"><i class="bi bi-dash"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Add as many CTA buttons as you want.</small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="primary_image" class="form-label">Primary Image</label>
                        @if($heroSlide->primary_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($heroSlide->primary_image) }}" alt="{{ $heroSlide->title }}" class="img-fluid rounded">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('primary_image') is-invalid @enderror" id="primary_image" name="primary_image" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                        @error('primary_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="overlay_image" class="form-label">Overlay Image</label>
                        @if($heroSlide->overlay_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($heroSlide->overlay_image) }}" alt="Overlay" class="img-fluid rounded">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('overlay_image') is-invalid @enderror" id="overlay_image" name="overlay_image" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                        @error('overlay_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $heroSlide->order) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Slide
                </button>
                <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const repeater = document.getElementById('cta-repeater');
    function updateNames() {
        repeater.querySelectorAll('.cta-row').forEach(function(row, idx) {
            row.querySelectorAll('input').forEach(function(input) {
                if (input.name.includes('[text]')) input.name = `ctas[${idx}][text]`;
                if (input.name.includes('[link]')) input.name = `ctas[${idx}][link]`;
            });
        });
    }
    repeater.addEventListener('click', function(e) {
        if (e.target.closest('.add-cta')) {
            const lastRow = repeater.querySelector('.cta-row:last-child');
            const newRow = lastRow.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            repeater.appendChild(newRow);
            updateNames();
        }
        if (e.target.closest('.remove-cta')) {
            if (repeater.querySelectorAll('.cta-row').length > 1) {
                e.target.closest('.cta-row').remove();
                updateNames();
            }
        }
    });
});
</script>
@endpush
        </form>
    </div>
</div>
@endsection
