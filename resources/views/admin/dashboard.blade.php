@extends('admin.layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your platform')

@push('styles')
<style>
    .stats-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .stats-card .card-body {
        padding: 1.5rem;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        margin-top: 0.5rem;
    }
    
    .chart-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        height: 100%;
    }
    
    .activity-item {
        padding: 1rem;
        border-left: 3px solid #e9ecef;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
        transition: all 0.2s;
    }
    
    .activity-item:hover {
        background: #e9ecef;
        border-left-color: #667eea;
    }
    
    .quick-action-btn {
        border-radius: 10px;
        padding: 1rem;
        border: 2px dashed #dee2e6;
        transition: all 0.3s;
        text-align: center;
        display: block;
        text-decoration: none;
        color: #495057;
    }
    
    .quick-action-btn:hover {
        border-color: #667eea;
        background: #f8f9ff;
        color: #667eea;
        transform: translateY(-2px);
    }
    
    .badge-new {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Export Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.dashboard.export-pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
            <a href="{{ route('admin.dashboard.export-excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats Overview -->
<div class="row g-3 mb-4">
    <!-- Total Content Stats -->
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 20%, #764ba2 100%); color: white;">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-value">{{ $stats['news'] }}</div>
                <div class="stat-label">News Articles</div>
                @if($weeklyStats['news'] > 0)
                    <div class="stat-badge bg-success bg-opacity-10 text-success">
                        <i class="fas fa-arrow-up"></i> {{ $weeklyStats['news'] }} this week
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-value">{{ $stats['events'] }}</div>
                <div class="stat-label">Events</div>
                @if($weeklyStats['events'] > 0)
                    <div class="stat-badge bg-info bg-opacity-10 text-info">
                        <i class="fas fa-plus"></i> {{ $weeklyStats['events'] }} this week
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-value">{{ $stats['contact_messages'] }}</div>
                <div class="stat-label">Contact Messages</div>
                @if($stats['unread_messages'] > 0)
                    <div class="stat-badge bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-bell"></i> {{ $stats['unread_messages'] }} unread
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ $stats['communities'] }}</div>
                <div class="stat-label">Communities</div>
                @if($stats['pending_communities'] > 0)
                    <div class="stat-badge bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-clock"></i> {{ $stats['pending_communities'] }} pending
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #e9ecef; color: #667eea; width: 50px; height: 50px; margin-bottom: 0;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['programs'] }}</div>
                        <div class="stat-label">Programs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #e9ecef; color: #f5576c; width: 50px; height: 50px; margin-bottom: 0;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['team_members'] }}</div>
                        <div class="stat-label">Team Members</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #e9ecef; color: #4facfe; width: 50px; height: 50px; margin-bottom: 0;">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['gallery_images'] }}</div>
                        <div class="stat-label">Gallery Images</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #e9ecef; color: #43e97b; width: 50px; height: 50px; margin-bottom: 0;">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['testimonials'] }}</div>
                        <div class="stat-label">Testimonials</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Analytics Row -->
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="chart-card card">
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Activity Overview</h5>
                <small class="text-muted">Last 7 days content creation trends</small>
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="chart-card card">
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-success"></i>Content Distribution</h5>
                <small class="text-muted">By category</small>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-newspaper me-2 text-primary"></i>Recent News</h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @forelse($recentNews as $news)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($news->title, 50) }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-tag"></i> {{ $news->category }} • 
                                    <i class="fas fa-calendar"></i> {{ $news->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if($news->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No news articles yet.</p>
                @endforelse
                @if($recentNews->count() > 0)
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                        View All News <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-danger"></i>Upcoming Events</h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @forelse($upcomingEvents as $event)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($event->title, 50) }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ $event->event_date->format('M d, Y') }} • 
                                    <i class="fas fa-map-marker-alt"></i> {{ Str::limit($event->location, 25) }}
                                </small>
                            </div>
                            @if($event->is_featured)
                                <span class="badge badge-new">Featured</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No upcoming events.</p>
                @endforelse
                @if($upcomingEvents->count() > 0)
                    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-danger btn-sm w-100 mt-2">
                        View All Events <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Messages & Communities -->
<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-envelope me-2 text-info"></i>Recent Messages</h5>
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                @forelse($recentMessages as $message)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $message->name }}</h6>
                                <small class="text-muted d-block mb-1">{{ $message->email }}</small>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $message->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if($message->status === 'new')
                                <span class="badge bg-warning">New</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No messages yet.</p>
                @endforelse
                @if($recentMessages->count() > 0)
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-info btn-sm w-100 mt-2">
                        View All Messages <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-success"></i>Recent Communities</h5>
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                @forelse($recentCommunities as $community)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $community->name }}</h6>
                                <small class="text-muted d-block mb-1">{{ $community->location }}</small>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $community->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if($community->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($community->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Declined</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No communities yet.</p>
                @endforelse
                @if($recentCommunities->count() > 0)
                    <a href="{{ route('admin.communities.index') }}" class="btn btn-outline-success btn-sm w-100 mt-2">
                        View All Communities <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('admin.news.create') }}" class="quick-action-btn">
                            <i class="fas fa-plus-circle fa-2x mb-2 text-primary"></i>
                            <div class="small fw-bold">Add News</div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('admin.events.create') }}" class="quick-action-btn">
                            <i class="fas fa-calendar-plus fa-2x mb-2 text-danger"></i>
                            <div class="small fw-bold">Add Event</div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('admin.programs.create') }}" class="quick-action-btn">
                            <i class="fas fa-graduation-cap fa-2x mb-2 text-warning"></i>
                            <div class="small fw-bold">Add Program</div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('admin.team-members.create') }}" class="quick-action-btn">
                            <i class="fas fa-user-plus fa-2x mb-2 text-success"></i>
                            <div class="small fw-bold">Add Member</div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('admin.gallery.create') }}" class="quick-action-btn">
                            <i class="fas fa-image fa-2x mb-2 text-info"></i>
                            <div class="small fw-bold">Add Gallery</div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('admin.settings.index') }}" class="quick-action-btn">
                            <i class="fas fa-cog fa-2x mb-2 text-secondary"></i>
                            <div class="small fw-bold">Settings</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'News',
                    data: {!! json_encode($chartData['news']) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Events',
                    data: {!! json_encode($chartData['events']) !!},
                    borderColor: '#f5576c',
                    backgroundColor: 'rgba(245, 87, 108, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Messages',
                    data: {!! json_encode($chartData['messages']) !!},
                    borderColor: '#4facfe',
                    backgroundColor: 'rgba(79, 172, 254, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const newsCategories = {!! json_encode($newsCategories->pluck('category')) !!};
    const newsCounts = {!! json_encode($newsCategories->pluck('total')) !!};
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: newsCategories,
            datasets: [{
                data: newsCounts,
                backgroundColor: [
                    '#667eea',
                    '#f5576c',
                    '#4facfe',
                    '#43e97b',
                    '#fa709a',
                    '#feca57'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
