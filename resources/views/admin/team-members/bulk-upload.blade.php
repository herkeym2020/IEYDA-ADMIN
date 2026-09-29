@extends('admin.layouts.app')

@section('page-title', 'Bulk Upload Team Members')
@section('page-subtitle', 'Import many team members via CSV')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-upload"></i> Bulk Upload Team Members</span>
        <a href="{{ route('admin.team-members.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
    </div>
    <div class="card-body">
        @if(session('warning'))
            <div class="alert alert-warning">
                <strong>{{ session('warning') }}</strong>
                @if(session('import_errors'))
                    <ul class="mt-2 mb-0">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.team-members.bulkUpload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">CSV File</label>
                <input type="file" name="file" accept=".csv,text/csv" class="form-control" required>
                @error('file')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <div class="form-text">Required headers: <code>type, name, position</code>. Optional headers: executive_type, term, department, order, priority, is_active, email, phone, location, bio, achievements, awards, salute, image_url.</div>
            </div>
            <button type="submit" class="btn btn-primary">Upload & Import</button>
        </form>

        <h6>Sample CSV</h6>
        <pre class="bg-light p-3 border rounded small">type,name,position,executive_type,term,department,order,priority,is_active,email,phone,location,bio,achievements,awards,salute,image_url
executive_present,John Doe,President,present,2024-2026,Leadership,1,1,true,john@example.com,+2348012345678,Ilorin,"Leads the team","Leadership Award|Community Builder",,,https://example.com/photo1.jpg
executive_pioneering,Jane Smith,Vice President,pioneering,2020-2022,Leadership,2,2,true,jane@example.com,+2348098765432,Ilorin,"Supports operations","Impact Maker",,,https://example.com/photo2.jpg
staff,Ahmed Ali,Program Manager,, ,Programs,3,3,true,ahmed@example.com,,Kwara,,"Project Lead",,,https://example.com/photo3.jpg</pre>

        <p class="text-muted small mb-0">Notes: <br>- <strong>type</strong> must be one of grand_patron, board_of_trustees, executive_present, executive_pioneering, staff, volunteer.<br>- <strong>achievements</strong> can be pipe-separated (e.g. <code>Leadership Award|Community Builder</code>).<br>- <strong>is_active</strong> accepts true/false/1/0.<br>- <strong>image_url</strong> is optional; if provided we attempt to download and store it.</p>
    </div>
</div>
@endsection
