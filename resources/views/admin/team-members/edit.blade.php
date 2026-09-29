@extends('admin.layouts.app')

@section('page-title', 'Edit Team Member')
@section('page-subtitle', 'Update team profile')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-pencil"></i> Edit Team Member
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
    <form action="{{ route('admin.team-members.update', $teamMember) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required onchange="toggleTeamFields()">
              <option value="grand_patron" {{ old('type', $teamMember->type)==='grand_patron' ? 'selected' : '' }}>Grand Patron</option>
              <option value="board_of_trustees" {{ old('type', $teamMember->type)==='board_of_trustees' ? 'selected' : '' }}>Board of Trustees</option>
              <option value="executive_present" {{ old('type', $teamMember->type)==='executive_present' ? 'selected' : '' }}>Executive (Present)</option>
              <option value="executive_pioneering" {{ old('type', $teamMember->type)==='executive_pioneering' ? 'selected' : '' }}>Executive (Pioneering)</option>
              <option value="staff" {{ old('type', $teamMember->type)==='staff' ? 'selected' : '' }}>Staff</option>
              <option value="volunteer" {{ old('type', $teamMember->type)==='volunteer' ? 'selected' : '' }}>Volunteer</option>
            </select>
            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3" id="salute-field" style="display: {{ old('type', $teamMember->type) === 'grand_patron' ? 'block' : 'none' }};">
            <label for="salute" class="form-label">Salute/Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('salute') is-invalid @enderror" id="salute" name="salute" value="{{ old('salute', $teamMember->salute) }}">
            @error('salute')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3 d-none" id="awards-field">
            <label for="awards" class="form-label">Awards/Titles</label>
            <input type="text" class="form-control @error('awards') is-invalid @enderror" id="awards" name="awards" value="{{ old('awards', $teamMember->awards) }}">
            @error('awards')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3 d-none" id="executive-type-field">
            <label for="executive_type" class="form-label">Executive Type</label>
            <select class="form-select @error('executive_type') is-invalid @enderror" id="executive_type" name="executive_type">
              <option value="present" {{ old('executive_type', $teamMember->executive_type)==='present' ? 'selected' : '' }}>Present</option>
              <option value="pioneering" {{ old('executive_type', $teamMember->executive_type)==='pioneering' ? 'selected' : '' }}>Pioneering</option>
            </select>
            @error('executive_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3 d-none" id="term-field">
            <label for="term" class="form-label">Term/Year</label>
            <input type="text" class="form-control @error('term') is-invalid @enderror" id="term" name="term" value="{{ old('term', $teamMember->term) }}">
            @error('term')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $teamMember->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $teamMember->position) }}" required>
            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department', $teamMember->department) }}">
            @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="6">{{ old('bio', $teamMember->bio) }}</textarea>
            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $teamMember->email) }}">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $teamMember->phone) }}">
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $teamMember->location) }}" placeholder="e.g., Ilorin, Kwara State">
            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="achievements" class="form-label">Achievements</label>
            <textarea class="form-control @error('achievements') is-invalid @enderror" id="achievements" name="achievements" rows="4" placeholder="Enter achievements as comma-separated values (e.g., Leadership Excellence, Community Service, Educational Development)">{{ old('achievements', is_array($teamMember->achievements) ? implode(', ', $teamMember->achievements) : $teamMember->achievements) }}</textarea>
            <small class="text-muted">Enter achievements separated by commas</small>
            @error('achievements')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Social Links</label>
            <div id="social-links-container">
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addSocialLink()"><i class="bi bi-plus"></i> Add Social Link</button>
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label for="image" class="form-label">Profile Photo</label>
            @if($teamMember->image)
              <div class="mb-2"><img src="{{ Storage::url($teamMember->image) }}" alt="{{ $teamMember->name }}" class="img-fluid rounded"></div>
            @endif
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
            <small class="text-muted">Leave empty to keep current photo</small>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>


          <div class="mb-3">
            <label for="order" class="form-label">Display Order</label>
            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $teamMember->order) }}" min="0">
            @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <input type="number" class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" value="{{ old('priority', $teamMember->priority ?? 0) }}" min="0" max="10">
            <small class="text-muted">Higher numbers appear first (0-10)</small>
            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Member</button>
        <a href="{{ route('admin.team-members.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
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
    function toggleTeamFields() {
      const type = document.getElementById('type').value;
      document.getElementById('salute-field').classList.toggle('d-none', type !== 'grand_patron');
      document.getElementById('awards-field').classList.toggle('d-none', type !== 'grand_patron');
      document.getElementById('executive-type-field').classList.toggle('d-none', !(type === 'executive_present' || type === 'executive_pioneering'));
      document.getElementById('term-field').classList.toggle('d-none', !(type === 'executive_present' || type === 'executive_pioneering'));
    }
    document.addEventListener('DOMContentLoaded', toggleTeamFields);
    </script>
@endsection
