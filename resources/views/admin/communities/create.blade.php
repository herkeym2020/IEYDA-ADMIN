@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Add New Community</h1>
    <form action="{{ route('admin.communities.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Community Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="lga" class="form-label">LGA</label>
            <input type="text" name="lga" id="lga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="contact_name" class="form-label">Contact Name</label>
            <input type="text" name="contact_name" id="contact_name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="contact_email" class="form-label">Contact Email</label>
            <input type="email" name="contact_email" id="contact_email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="contact_phone" class="form-label">Contact Phone</label>
            <input type="text" name="contact_phone" id="contact_phone" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Community</button>
    </form>
</div>
@endsection
