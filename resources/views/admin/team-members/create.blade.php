@extends('admin.layouts.app')

@section('page-title', 'Add Team Member')
@section('page-subtitle', 'Create a new team profile')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-plus-circle"></i> New Team Member
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>There were some problems with your input:</strong>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <form action="{{ route('admin.team-members.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required onchange="toggleTeamFields()">
              <option value="grand_patron" {{ old('type')==='grand_patron' ? 'selected' : '' }}>Grand Patron</option>
              <option value="board_of_trustees" {{ old('type')==='board_of_trustees' ? 'selected' : '' }}>Board of Trustees</option>
              <option value="executive_present" {{ old('type')==='executive_present' ? 'selected' : '' }}>Executive (Present)</option>
              <option value="executive_pioneering" {{ old('type')==='executive_pioneering' ? 'selected' : '' }}>Executive (Pioneering)</option>
              <option value="staff" {{ old('type')==='staff' ? 'selected' : '' }}>Staff</option>
              <option value="volunteer" {{ old('type')==='volunteer' ? 'selected' : '' }}>Volunteer</option>
            </select>
            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3" id="salute-field" style="display: {{ old('type', 'grand_patron') === 'grand_patron' ? 'block' : 'none' }};">
            <label for="salute" class="form-label">Salute/Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('salute') is-invalid @enderror" id="salute" name="salute" value="{{ old('salute') }}">
            @error('salute')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3 d-none" id="awards-field">
            <label for="awards" class="form-label">Awards/Titles</label>
            <input type="text" class="form-control @error('awards') is-invalid @enderror" id="awards" name="awards" value="{{ old('awards') }}">
            @error('awards')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3 d-none" id="executive-type-field">
            <label for="executive_type" class="form-label">Executive Type</label>
            <select class="form-select @error('executive_type') is-invalid @enderror" id="executive_type" name="executive_type">
              <option value="present" {{ old('executive_type')==='present' ? 'selected' : '' }}>Present</option>
              <option value="pioneering" {{ old('executive_type')==='pioneering' ? 'selected' : '' }}>Pioneering</option>
            </select>
            @error('executive_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3 d-none" id="term-field">
            <label for="term" class="form-label">Term/Year</label>
            <input type="text" class="form-control @error('term') is-invalid @enderror" id="term" name="term" value="{{ old('term') }}">
            @error('term')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" required>
            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department') }}">
            @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="6">{{ old('bio') }}</textarea>
            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="e.g., Ilorin, Kwara State">
            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="achievements" class="form-label">Achievements</label>
            <textarea class="form-control @error('achievements') is-invalid @enderror" id="achievements" name="achievements" rows="4" placeholder="Enter achievements as comma-separated values (e.g., Leadership Excellence, Community Service, Educational Development)">{{ old('achievements') }}</textarea>
            <small class="text-muted">Enter achievements separated by commas</small>
            @error('achievements')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Social Links</label>
            <div id="social-links-container"></div>
            <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addSocialLink()"><i class="bi bi-plus"></i> Add Social Link</button>
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label for="image" class="form-label">Profile Photo <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>


          <div class="mb-3">
            <label for="order" class="form-label">Display Order</label>
            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
            @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <input type="number" class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" value="{{ old('priority', 0) }}" min="0" max="10">
            <small class="text-muted">Higher numbers appear first (0-10)</small>
            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Create Member</button>
        <a href="{{ route('admin.team-members.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
let socialIndex = 0;
function addSocialLink(){
  const c = document.getElementById('social-links-container');
  const row = document.createElement('div');
  row.className = 'row g-2 mb-2';
  row.innerHTML = `
    <div class="col-md-4">
      <input type="text" class="form-control" name="social_links[${socialIndex}][platform]" placeholder="Platform (e.g., Twitter)">
    </div>
    <div class="col-md-7">
      <input type="url" class="form-control" name="social_links[${socialIndex}][url]" placeholder="https://...">
    </div>
    <div class="col-md-1 d-grid">
      <button type="button" class="btn btn-outline-danger" onclick="this.closest('div.row').remove()"><i class="bi bi-dash"></i></button>
    </div>`;
  socialIndex++;
  c.appendChild(row);
}
// Show/hide salute field based on type
document.getElementById('type').addEventListener('change', function() {
  const type = this.value;
  const saluteField = document.getElementById('salute-field');
  if(type === 'grand_patron') {
    saluteField.style.display = 'block';
  } else {
    saluteField.style.display = 'none';
  }
});
</script>
@endpush
@endsection
