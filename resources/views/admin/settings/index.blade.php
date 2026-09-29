@extends('admin.layouts.app')

@section('page-title', 'Settings')
@section('page-subtitle', 'Configure site settings')

@section('content')
<div class="modern-card">
    <div class="modern-card-header">
        <h3><i class="fas fa-cogs"></i> Site Settings</h3>
    </div>
    <div class="modern-card-body form-modern">
    <form action="{{ route('admin.settings.bulk-update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($settings->isEmpty())
                <div class="alert alert-info">No settings found.</div>
            @else
                <ul class="nav nav-tabs-modern" id="settingsTabs" role="tablist">
                    @foreach($settings as $group => $groupSettings)
                        @php $tabId = 'tab-' . \Illuminate\Support\Str::slug($group); @endphp
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $tabId }}-tab" data-toggle="tab" href="#{{ $tabId }}" role="tab" aria-controls="{{ $tabId }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ ucfirst($group) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content p-4 border border-top-0 rounded-bottom bg-white">
                    @foreach($settings as $group => $groupSettings)
                        @php $tabId = 'tab-' . \Illuminate\Support\Str::slug($group); @endphp
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel" aria-labelledby="{{ $tabId }}-tab">
                            <div class="row">
                                @foreach($groupSettings as $setting)
                                    @php
                                        $label = ucwords(str_replace('_', ' ', $setting->key));
                                        $isFile = \Illuminate\Support\Str::contains($setting->key, ['logo','favicon','icon','image','og_image','avatar']);
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <label for="setting_{{ $setting->id }}" class="form-label">{{ $label }}</label>

                                        @if($isFile)
                                            @if($setting->value)
                                                <div class="mb-2">
                                                    <img src="{{ Storage::url($setting->value) }}" alt="{{ $label }}" class="img-thumbnail" style="max-height: 80px;">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" id="setting_{{ $setting->id }}" name="settings_files[{{ $setting->id }}]" accept="image/*">
                                            <small class="text-muted">Leave blank to keep current file.</small>
                                        @else
                                            @if(strlen($setting->value) > 100)
                                                <textarea class="form-control" id="setting_{{ $setting->id }}" name="settings[{{ $setting->id }}]" rows="4">{{ $setting->value }}</textarea>
                                            @else
                                                <input type="text" class="form-control" id="setting_{{ $setting->id }}" name="settings[{{ $setting->id }}]" value="{{ $setting->value }}">
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-modern btn-modern-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Activate tab based on URL hash if present
    var hash = window.location.hash;
    if (hash) {
        var triggerEl = document.querySelector('#settingsTabs a[href="' + hash + '"]');
        if (triggerEl) {
            $(triggerEl).tab('show');
        }
    }
    // Update hash when tab is shown
    $('#settingsTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var href = e.target.getAttribute('href');
        if (href) {
            history.replaceState(null, '', href);
        }
    });
});
</script>
@endpush
