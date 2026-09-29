@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Community</h1>
    <form action="{{ route('admin.communities.update', $community) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Community Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $community->name }}" required>
        </div>
        <div class="mb-3">
            <label for="lga" class="form-label">LGA</label>
            <input type="text" name="lga" id="lga" class="form-control" value="{{ $community->lga }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $community->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="contact_name" class="form-label">Contact Name</label>
            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ $community->contact_name }}">
        </div>
        <div class="mb-3">
            <label for="contact_email" class="form-label">Contact Email</label>
            <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ $community->contact_email }}">
        </div>
        <div class="mb-3">
            <label for="contact_phone" class="form-label">Contact Phone</label>
            <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ $community->contact_phone }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Community</button>
    </form>
</div>
@endsection
