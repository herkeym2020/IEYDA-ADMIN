@extends('admin.layouts.app')

@section('page-title', 'SEO Editor')
@section('page-subtitle', 'Optimize your content for search engines')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-search"></i> SEO Metadata
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.seo.update', [$type, $model->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="meta_title">Meta Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" 
                               name="meta_title" value="{{ old('meta_title', $seo->meta_title) }}" 
                               maxlength="60" placeholder="Your SEO title (max 60 chars)">
                        <small class="form-text text-muted d-block mt-2">
                            Length: <span id="titleLength">{{ strlen($seo->meta_title ?? '') }}</span>/60 chars
                        </small>
                        @error('meta_title')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="meta_description">Meta Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                  name="meta_description" rows="3" maxlength="160" 
                                  placeholder="Your meta description (max 160 chars)">{{ old('meta_description', $seo->meta_description) }}</textarea>
                        <small class="form-text text-muted d-block mt-2">
                            Length: <span id="descLength">{{ strlen($seo->meta_description ?? '') }}</span>/160 chars
                        </small>
                        @error('meta_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="slug">URL Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                               name="slug" value="{{ old('slug', $seo->slug) }}" placeholder="url-friendly-slug">
                        @error('slug')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="keywords">Keywords</label>
                        <input type="text" class="form-control" id="keywords" name="keywords"
                               value="{{ old('keywords', $seo->keywords) }}" placeholder="keyword1, keyword2, keyword3">
                        <small class="form-text text-muted d-block mt-2">Comma-separated keywords</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="og_image">Open Graph Image</label>
                        <div class="d-flex gap-3 align-items-start">
                            <div style="flex: 1;">
                                <input type="file" class="form-control @error('og_image') is-invalid @enderror" id="og_image"
                                       name="og_image" accept="image/*">
                                <small class="form-text text-muted d-block mt-2">Recommended: 1200x630px</small>
                                @error('og_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            @if($seo->og_image)
                            <div style="flex: 0 0 150px;">
                                <img src="{{ Storage::url($seo->og_image) }}" alt="OG Image" class="img-fluid rounded">
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save SEO Metadata
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="suggestionsBtn">
                            <i class="fas fa-lightbulb"></i> Get Suggestions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- SEO Score Card -->
        <div class="modern-card mb-3">
            <div class="card-header" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <h6 class="mb-0">SEO Score</h6>
            </div>
            <div class="card-body text-center">
                <div style="font-size: 3rem; font-weight: bold; color: #667eea; margin: 1rem 0;">
                    {{ $seo->calculateScore() === 'excellent' ? '🟢' : ($seo->calculateScore() === 'good' ? '🟡' : '🔴') }}
                </div>
                <h4 class="text-capitalize">{{ $seo->calculateScore() }}</h4>
                <div style="margin-top: 1rem;">
                    <small class="d-block text-muted">
                        <i class="fas fa-check text-success"></i> Meta Title: {{ $seo->has_meta_title ? '✓' : '✗' }}
                    </small>
                    <small class="d-block text-muted">
                        <i class="fas fa-check text-success"></i> Meta Description: {{ $seo->has_meta_description ? '✓' : '✗' }}
                    </small>
                    <small class="d-block text-muted">
                        <i class="fas fa-check text-success"></i> Keywords: {{ !empty($seo->keywords) ? '✓' : '✗' }}
                    </small>
                    <small class="d-block text-muted">
                        <i class="fas fa-check text-success"></i> OG Image: {{ $seo->og_image ? '✓' : '✗' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="modern-card mb-3">
            <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <h6 class="mb-0">Search Preview</h6>
            </div>
            <div class="card-body">
                <div style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px; background: #f9f9f9;">
                    <h5 style="color: #1a0ddb; margin-bottom: 0.25rem; font-size: 1.1rem;">
                        {{ $seo->meta_title ?? $model->title ?? 'Your Title' }}
                    </h5>
                    <small style="color: #006621; display: block; margin-bottom: 0.5rem;">
                        example.com › {{ $seo->slug ?? 'your-slug' }}
                    </small>
                    <p style="color: #545454; font-size: 0.9rem; margin: 0;">
                        {{ $seo->meta_description ?? 'Your meta description will appear here...' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Content Info Card -->
        <div class="modern-card">
            <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h6 class="mb-0">Content Stats</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Word Count:</span>
                    <strong>{{ $seo->word_count ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Last Updated:</span>
                    <strong>{{ $seo->updated_at->diffForHumans() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.modern-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.modern-card .card-header {
    border-radius: 12px 12px 0 0;
    padding: 1rem 1.5rem;
    border: none;
}
</style>

<script>
$(document).ready(function() {
    // Character count for title
    $('#meta_title').on('input', function() {
        $('#titleLength').text($(this).val().length);
    });

    // Character count for description
    $('#meta_description').on('input', function() {
        $('#descLength').text($(this).val().length);
    });

    // Get SEO Suggestions
    $('#suggestionsBtn').click(function() {
        $.ajax({
            url: '{{ route("admin.seo.suggestions", [$type, $model->id]) }}',
            type: 'GET',
            success: function(data) {
                $('#meta_title').val(data.title).trigger('input');
                $('#meta_description').val(data.description).trigger('input');
                $('#keywords').val(data.keywords);
                alert('Suggestions loaded! Review and adjust as needed.');
            },
            error: function() {
                alert('Error loading suggestions. Please try again.');
            }
        });
    });
});
</script>
@endsection
