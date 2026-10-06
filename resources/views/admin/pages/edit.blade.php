@extends('admin.layouts.app')

@section('page-title', $page->slug === 'ilorin-history' ? 'Edit Ilorin History' : 'Edit Page')
@section('page-subtitle', $page->slug === 'ilorin-history' ? 'Manage the public Ilorin heritage story' : 'Update page content')

@section('content')
@php($metadata = $page->metadata ?? [])
<div class="card">
  <div class="card-header d-flex align-items-center justify-content-between">
    <span><i class="bi bi-pencil"></i> {{ $page->slug === 'ilorin-history' ? 'Ilorin History Editor' : 'Edit Page' }}</span>
    @if($page->slug === 'ilorin-history')<span class="badge badge-success">Public page connected</span>@endif
  </div>
  <div class="card-body">
    <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST" id="page-editor-form">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="title" class="form-label">Page title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Page summary</label>
        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4">{{ old('content', $page->content) }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      @if($page->slug === 'ilorin-history')
      <input type="hidden" name="metadata" id="metadata" value="">
      <div class="alert alert-info"><strong>How this works:</strong> edit the cards below, add or remove items, then save. The public history page updates from these fields; no JSON knowledge is required.</div>

      <div class="border rounded p-3 mb-4">
        <h5><i class="fas fa-heading text-primary"></i> Introduction</h5>
        <div class="mb-3"><label class="form-label">Eyebrow</label><input class="form-control" data-meta="eyebrow" value="{{ $metadata['eyebrow'] ?? '' }}" placeholder="A living heritage"></div>
        <div><label class="form-label">Introduction</label><textarea class="form-control" data-meta="intro" rows="3">{{ $metadata['intro'] ?? '' }}</textarea></div>
      </div>

      <div class="border rounded p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0"><i class="fas fa-chart-bar text-primary"></i> Highlight statistics</h5><button type="button" class="btn btn-sm btn-outline-primary" data-add="stat">Add statistic</button></div>
        <div id="stats-list" class="row g-3"></div>
      </div>

      <div class="border rounded p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0"><i class="fas fa-images text-primary"></i> Heritage gallery</h5><button type="button" class="btn btn-sm btn-outline-primary" data-add="gallery">Add image</button></div>
        <div id="gallery-list"></div>
      </div>

      <div class="border rounded p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0"><i class="fas fa-clock text-primary"></i> Timeline milestones</h5><button type="button" class="btn btn-sm btn-outline-primary" data-add="timeline">Add milestone</button></div>
        <div id="timeline-list"></div>
      </div>

      <div class="border rounded p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0"><i class="fas fa-users text-primary"></i> People who shaped the story</h5><button type="button" class="btn btn-sm btn-outline-primary" data-add="person">Add person</button></div>
        <div id="people-list"></div>
      </div>

      <div class="border rounded p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0"><i class="fas fa-link text-primary"></i> Further reading</h5><button type="button" class="btn btn-sm btn-outline-primary" data-add="source">Add source</button></div>
        <div id="sources-list"></div>
      </div>
      @endif

      <div class="d-flex gap-2"><button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save changes</button><a href="{{ route('admin.pages.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a></div>
    </form>
  </div>
</div>
@endsection

@if($page->slug === 'ilorin-history')
@push('styles')
<style>
  .history-editor-item { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: .5rem; padding: 1rem; margin-bottom: .75rem; }
  .history-editor-item .remove-item { color: #b42318; }
  .history-editor-empty { color: #6b7280; padding: .75rem 0; }
</style>
@endpush
@push('scripts')
<script>
(() => {
  const existing = @json($metadata);
  const lists = { stat: 'stats-list', gallery: 'gallery-list', timeline: 'timeline-list', person: 'people-list', source: 'sources-list' };
  const esc = (value = '') => String(value).replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;');
  const input = (label, key, value, type = 'text', wide = false) => `<div class="${wide ? 'col-12' : 'col-md-6'}"><label class="form-label">${label}</label><${type === 'textarea' ? 'textarea' : 'input'} class="form-control" data-field="${key}" ${type !== 'textarea' ? `type="${type}"` : ''}>${type === 'textarea' ? esc(value) : ''}</${type === 'textarea' ? 'textarea' : 'input'}></div>`;
  const render = (kind, item = {}) => {
    const wrap = document.createElement('div'); wrap.className = 'history-editor-item'; wrap.dataset.kind = kind;
    let fields = '';
    if (kind === 'stat') fields = `<div class="row">${input('Label', 'label', item.label)}${input('Value', 'value', item.value)}</div>`;
    if (kind === 'gallery') fields = `<div class="row">${input('Image URL or public path', 'image', item.image, 'text', true)}${input('Caption', 'caption', item.caption)}${input('Alt text', 'alt', item.alt)}</div>`;
    if (kind === 'timeline') fields = `<div class="row">${input('Year / period', 'year', item.year)}${input('Title', 'title', item.title)}${input('Description', 'description', item.description, 'textarea', true)}${input('Image URL or public path', 'image', item.image, 'text', true)}</div>`;
    if (kind === 'person') fields = `<div class="row">${input('Name', 'name', item.name)}${input('Role', 'role', item.role)}${input('Description', 'description', item.description, 'textarea', true)}${input('Portrait URL or public path', 'image', item.image, 'text', true)}</div>`;
    if (kind === 'source') fields = `<div class="row">${input('Label', 'label', item.label)}${input('URL', 'url', item.url)}</div>`;
    wrap.innerHTML = `${fields}<div class="text-end mt-2"><button type="button" class="btn btn-sm btn-link remove-item">Remove</button></div>`;
    wrap.querySelector('.remove-item').addEventListener('click', () => wrap.remove());
    return wrap;
  };
  const add = (kind, item) => document.getElementById(lists[kind]).appendChild(render(kind, item));
  document.querySelectorAll('[data-add]').forEach((button) => button.addEventListener('click', () => add(button.dataset.add, {})));
  (existing.stats || []).forEach((item) => add('stat', item));
  (existing.gallery || []).forEach((item) => add('gallery', item));
  (existing.timeline || []).forEach((item) => add('timeline', item));
  (existing.people || []).forEach((item) => add('person', item));
  (existing.sources || []).forEach((item) => add('source', item));
  const form = document.getElementById('page-editor-form');
  form.addEventListener('submit', () => {
    const metadata = { eyebrow: document.querySelector('[data-meta="eyebrow"]').value, intro: document.querySelector('[data-meta="intro"]').value };
    const keys = { stat: 'stats', gallery: 'gallery', timeline: 'timeline', person: 'people', source: 'sources' };
    Object.entries(lists).forEach(([kind, id]) => {
      metadata[keys[kind]] = [...document.querySelectorAll(`#${id} [data-kind="${kind}"]`)].map((row) => Object.fromEntries([...row.querySelectorAll('[data-field]')].map((field) => [field.dataset.field, field.value.trim()])));
    });
    document.getElementById('metadata').value = JSON.stringify(metadata);
  });
})();
</script>
@endpush
@endif
