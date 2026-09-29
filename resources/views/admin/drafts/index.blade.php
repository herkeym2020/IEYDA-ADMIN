@extends('admin.layouts.app')

@section('page-title', 'Drafts')
@section('page-subtitle', 'Manage content saved as draft across types')

@section('content')
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.drafts.index') }}" class="btn btn-sm" style="background-color: #667eea; color: white;">
                <i class="fas fa-file-alt me-1"></i> All Drafts
            </a>
            <a href="{{ route('admin.drafts.scheduled') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-calendar me-1"></i> Scheduled
            </a>
        </div>
    </div>
    </div>

@php
    $totalDrafts = ($newsDrafts->count() + $eventDrafts->count() + $programDrafts->count());
@endphp

@if($totalDrafts > 0)
    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-2"><i class="fas fa-newspaper"></i> News</h5>
            @forelse($newsDrafts as $item)
                <div class="modern-card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <small class="text-muted d-block">Updated {{ $item->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.drafts.publish') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="news">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Publish</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No news drafts.</p>
            @endforelse
        </div>

        <div class="col-12 mt-3">
            <h5 class="mb-2"><i class="fas fa-calendar-alt"></i> Events</h5>
            @forelse($eventDrafts as $item)
                <div class="modern-card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <small class="text-muted d-block">Updated {{ $item->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.events.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.drafts.publish') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="events">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Publish</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No event drafts.</p>
            @endforelse
        </div>

        <div class="col-12 mt-3">
            <h5 class="mb-2"><i class="fas fa-briefcase"></i> Programs</h5>
            @forelse($programDrafts as $item)
                <div class="modern-card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            <small class="text-muted d-block">Updated {{ $item->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.programs.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.drafts.publish') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="programs">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Publish</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No program drafts.</p>
            @endforelse
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No drafts found. Start creating content!
    </div>
@endif

<style>
.modern-card { border-radius: 12px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; }
.modern-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
</style>
@endsection
