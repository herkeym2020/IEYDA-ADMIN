@extends('admin.layouts.app')

@section('page-title', 'Pages')
@section('page-subtitle', 'Manage static pages')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-file-earmark-text"></i> Pages
  </div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th>Slug</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pages as $page)
        <tr>
          <td>{{ $page->title }}</td>
          <td>{{ $page->slug }}</td>
          <td>
            <a href="{{ route('admin.pages.edit', $page->slug) }}" class="btn btn-sm btn-outline-primary">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
