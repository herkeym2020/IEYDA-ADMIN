@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Contact Messages</h2>
        <form method="GET" action="" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <form method="POST" action="{{ route('admin.contact-messages.bulkDelete') }}">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $msg->id }}"></td>
                        <td>{{ $msg->name }}</td>
                        <td>{{ $msg->email }}</td>
                        <td>{{ $msg->subject }}</td>
                        <td>{{ $msg->category }}</td>
                        <td>{{ $msg->status }}</td>
                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contact-messages.show', $msg) }}" class="btn btn-sm btn-info">View</a>
                            <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-danger mt-2" onclick="return confirm('Delete selected messages?')">Bulk Delete</button>
    </form>
    <div class="mt-3">
        {{ $messages->links() }}
    </div>
</div>
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        for (const cb of checkboxes) {
            cb.checked = this.checked;
        }
    });
</script>
@endsection
