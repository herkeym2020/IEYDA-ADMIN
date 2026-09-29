@extends('admin.layouts.app')

@section('page-title', 'Scheduled Content')
@section('page-subtitle', 'Manage scheduled items across News, Events, Programs')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.drafts.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Back to Drafts
        </a>
    </div>
    </div>

@php
    $totalScheduled = ($newsScheduled->count() + $eventScheduled->count() + $programScheduled->count());
@endphp

@if($totalScheduled > 0)
    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-2"><i class="fas fa-newspaper"></i> News</h5>
            @forelse($newsScheduled as $item)
                <div class="modern-card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <small class="text-muted d-block">
                                <i class="fas fa-clock"></i>
                                {{ optional($item->scheduled_at)->format('M d, Y H:i') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                            @if($item->scheduled_at && $item->scheduled_at <= now())
                            <form action="{{ route('admin.drafts.publish') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="news">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-success" title="Publish this content"><i class="fas fa-paper-plane"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No news scheduled.</p>
            @endforelse
        </div>

        <div class="col-12 mt-3">
            <h5 class="mb-2"><i class="fas fa-calendar-alt"></i> Events</h5>
            @forelse($eventScheduled as $item)
                <div class="modern-card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <small class="text-muted d-block">
                                <i class="fas fa-clock"></i>
                                {{ optional($item->scheduled_at)->format('M d, Y H:i') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.events.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                            @if($item->scheduled_at && $item->scheduled_at <= now())
                            <form action="{{ route('admin.drafts.publish') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="events">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-success" title="Publish this content"><i class="fas fa-paper-plane"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No events scheduled.</p>
            @endforelse
        </div>

        <div class="col-12 mt-3">
            <h5 class="mb-2"><i class="fas fa-briefcase"></i> Programs</h5>
            @forelse($programScheduled as $item)
                <div class="modern-card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <small class="text-muted d-block">
                                <i class="fas fa-clock"></i>
                                {{ optional($item->scheduled_at)->format('M d, Y H:i') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.programs.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                            @if($item->scheduled_at && $item->scheduled_at <= now())
                            <form action="{{ route('admin.drafts.publish') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="programs">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-success" title="Publish this content"><i class="fas fa-paper-plane"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No programs scheduled.</p>
            @endforelse
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No scheduled content at this time.
    </div>
@endif

<style>
.modern-card { border-radius: 12px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; }
.modern-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
</style>
@endsection
