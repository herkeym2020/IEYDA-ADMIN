@push('scripts')
<script>
function addObjective(){
  const c=document.getElementById('objectives-container');
  const d=document.createElement('div');
  d.className='input-group mb-2';
  d.innerHTML=`<input type="text" class="form-control" name="objectives[]" placeholder="Add an objective">
  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>`;
  c.appendChild(d);
}
function addAchievement(){
  const c=document.getElementById('achievements-container');
  const d=document.createElement('div');
  d.className='input-group mb-2';
  d.innerHTML=`<input type="text" class="form-control" name="achievements[]" placeholder="Add an achievement">
  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>`;
  c.appendChild(d);
}
function addPartner(){
  const c=document.getElementById('partners-container');
  const d=document.createElement('div');
  d.className='input-group mb-2';
  d.innerHTML=`<input type="text" class="form-control" name="partners[]" placeholder="Add a partner">
  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>`;
  c.appendChild(d);
}
function addLocation(){
  const c=document.getElementById('locations-container');
  const d=document.createElement('div');
  d.className='input-group mb-2';
  d.innerHTML=`<input type="text" class="form-control" name="locations[]" placeholder="Add a location">
  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>`;
  c.appendChild(d);
}
</script>
@endpush
@extends('admin.layouts.app')

@section('page-title', 'Edit Program')
@section('page-subtitle', 'Update program details')

@section('content')
<div class="card">
  <div class="card-header">
    <i class="bi bi-pencil"></i> Edit Program
  </div>
  <div class="card-body">
    <form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $program->title) }}" required>
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8" required>{{ old('description', $program->description) }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>



          <div class="mb-3">
            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $program->category) }}" required>
            @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Objectives</label>
            <div id="objectives-container">
              @foreach(old('objectives', $program->objectives ?? []) as $objective)
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="objectives[]" value="{{ $objective }}" placeholder="Add an objective">
                  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>
                </div>
              @endforeach
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="objectives[]" placeholder="Add an objective">
                <button type="button" class="btn btn-outline-success" onclick="addObjective()"><i class="bi bi-plus"></i></button>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Achievements</label>
            <div id="achievements-container">
              @foreach(old('achievements', $program->achievements ?? []) as $achievement)
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="achievements[]" value="{{ $achievement }}" placeholder="Add an achievement">
                  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>
                </div>
              @endforeach
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="achievements[]" placeholder="Add an achievement">
                <button type="button" class="btn btn-outline-success" onclick="addAchievement()"><i class="bi bi-plus"></i></button>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Partners</label>
            <div id="partners-container">
              @foreach(old('partners', $program->partners ?? []) as $partner)
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="partners[]" value="{{ $partner }}" placeholder="Add a partner">
                  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>
                </div>
              @endforeach
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="partners[]" placeholder="Add a partner">
                <button type="button" class="btn btn-outline-success" onclick="addPartner()"><i class="bi bi-plus"></i></button>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Locations</label>
            <div id="locations-container">
              @foreach(old('locations', $program->locations ?? []) as $location)
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="locations[]" value="{{ $location }}" placeholder="Add a location">
                  <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>
                </div>
              @endforeach
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="locations[]" placeholder="Add a location">
                <button type="button" class="btn btn-outline-success" onclick="addLocation()"><i class="bi bi-plus"></i></button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label for="image" class="form-label">Program Image <span class="text-danger">*</span></label>
            @if($program->image)
              <div class="mb-2"><img src="{{ Storage::url($program->image) }}" alt="{{ $program->title }}" class="img-fluid rounded"></div>
            @endif
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
            <small class="text-muted">Recommended: 1200x800px. Leave empty to keep current image</small>
            @error('image')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="icon" class="form-label">Icon (optional)</label>
            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $program->icon) }}" placeholder="Bootstrap icon name or custom class">
            @error('icon')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="beneficiaries" class="form-label">Beneficiaries</label>
            <input type="text" class="form-control @error('beneficiaries') is-invalid @enderror" id="beneficiaries" name="beneficiaries" value="{{ old('beneficiaries', $program->beneficiaries) }}" placeholder="e.g., 1000+">
            @error('beneficiaries')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="budget" class="form-label">Budget</label>
            <input type="text" class="form-control @error('budget') is-invalid @enderror" id="budget" name="budget" value="{{ old('budget', $program->budget) }}" placeholder="e.g., ₦50M">
            @error('budget')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $program->start_date) }}">
            @error('start_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $program->end_date) }}">
            @error('end_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="progress" class="form-label">Progress (%)</label>
            <input type="number" class="form-control @error('progress') is-invalid @enderror" id="progress" name="progress" value="{{ old('progress', $program->progress) }}" min="0" max="100">
            @error('progress')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="coordinator" class="form-label">Coordinator</label>
            <input type="text" class="form-control @error('coordinator') is-invalid @enderror" id="coordinator" name="coordinator" value="{{ old('coordinator', $program->coordinator) }}">
            @error('coordinator')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="order" class="form-label">Display Order</label>
            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $program->order) }}" min="0">
            @error('order')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
              <option value="active" {{ old('status', $program->status)==='active' ? 'selected' : '' }}>Active</option>
              <option value="upcoming" {{ old('status', $program->status)==='upcoming' ? 'selected' : '' }}>Upcoming</option>
              <option value="inactive" {{ old('status', $program->status)==='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $program->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>

          <div class="mb-3">
            <label for="scheduled_at" class="form-label">Schedule Publish (optional)</label>
            <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', optional($program->scheduled_at)->format('Y-m-d\TH:i')) }}">
            <small class="form-text text-muted">Set or update a future publish time.</small>
            @error('scheduled_at')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" name="draft_action" value="save_draft" class="btn btn-outline-secondary"><i class="bi bi-file-earmark"></i> Save Draft</button>
        <button type="submit" name="draft_action" value="schedule" class="btn btn-outline-primary"><i class="bi bi-clock"></i> Schedule</button>
        <button type="submit" name="draft_action" value="publish" class="btn btn-primary"><i class="bi bi-check-circle"></i> Publish Now</button>
        <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function addBenefit(){
  const c=document.getElementById('benefits-container');
  const d=document.createElement('div');
  d.className='input-group mb-2';
  d.innerHTML=`<input type=\"text\" class=\"form-control\" name=\"benefits[]\" placeholder=\"Add a benefit\">
  <button type=\"button\" class=\"btn btn-outline-success\" onclick=\"addBenefit()\"><i class=\"bi bi-plus\"></i></button>`;
  c.appendChild(d);
}
function addRequirement(){
  const c=document.getElementById('requirements-container');
  const d=document.createElement('div');
  d.className='input-group mb-2';
  d.innerHTML=`<input type=\"text\" class=\"form-control\" name=\"requirements[]\" placeholder=\"Add a requirement\">
  <button type=\"button\" class=\"btn btn-outline-success\" onclick=\"addRequirement()\"><i class=\"bi bi-plus\"></i></button>`;
  c.appendChild(d);
}
</script>
@endpush
@endsection
