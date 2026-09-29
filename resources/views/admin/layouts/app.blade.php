<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - IEYDA CMS</title>
    @php
        $adminFavicon = \App\Models\Setting::get('favicon');
    @endphp
    @if($adminFavicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($adminFavicon) }}">
    @endif
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AdminLTE CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap Icons (optional, for compatibility) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Modern Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-modern.css') }}">
    
    @stack('styles')
    <style>
        /* Fix sidebar to prevent it from scrolling with page content */
        .main-sidebar {
            position: fixed !important;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }
        
        /* Ensure content area has proper margin to account for fixed sidebar */
        .content-wrapper {
            margin-left: 250px;
        }
        
        /* When sidebar is collapsed */
        .sidebar-collapse .content-wrapper {
            margin-left: 0;
        }
        
        /* Smooth transition for sidebar collapse */
        .content-wrapper {
            transition: margin-left 0.3s ease-in-out;
        }
        
        /* Modern Sidebar Brand Area */
        .brand-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-bottom: none !important;
            padding: 1.5rem 1rem !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 0.5rem;
            min-height: 80px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .brand-link::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .brand-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .brand-image {
            width: 50px !important;
            height: 50px !important;
            border-radius: 12px !important;
            border: 3px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
            transition: all 0.3s ease;
            object-fit: cover;
            z-index: 1;
        }
        
        .brand-link:hover .brand-image {
            transform: scale(1.1) rotate(5deg);
            border-color: rgba(255, 255, 255, 0.6);
        }
        
        .brand-text {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: white !important;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 1;
            position: relative;
        }
        
        .brand-subtitle {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            z-index: 1;
        }
        
        .sidebar-collapse .brand-link {
            padding: 1rem 0.5rem !important;
        }
        
        .sidebar-collapse .brand-text,
        .sidebar-collapse .brand-subtitle {
            display: none;
        }
        
        /* Modern Navbar with Gradient & Glassmorphism */
        .main-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-bottom: none;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .main-header .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.5rem 0.75rem !important;
            margin: 0 0.25rem;
        }
        
        .main-header .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .main-header .navbar-nav .nav-link i {
            transition: transform 0.3s ease;
        }
        
        .main-header .navbar-nav .nav-link:hover i {
            transform: scale(1.1);
        }
        
        .navbar-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 0.6rem;
            padding: 3px 6px;
            background: #ff4757 !important;
            border: 2px solid #667eea;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Dynamic Time & Weather Display */
        .header-status {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        
        .header-status i {
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Enhanced User Profile */
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.5rem 1rem !important;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            color: white !important;
        }
        
        .user-dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        /* Modern Search Bar */
        .navbar-search {
            position: relative;
            max-width: 450px;
            flex: 1;
        }
        
        .navbar-search input {
            border-radius: 25px;
            padding: 0.6rem 1.2rem 0.6rem 2.8rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .navbar-search input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .navbar-search input:focus {
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            outline: none;
        }
        
        .navbar-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .navbar-search input:focus + i {
            color: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Enhanced Notifications */
        .notification-dropdown {
            position: relative;
        }
        
        .notification-dropdown .dropdown-menu {
            width: 360px;
            max-height: 450px;
            overflow-y: auto;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: none;
            margin-top: 0.5rem;
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s;
            position: relative;
        }
        
        .notification-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .notification-item:hover {
            background: linear-gradient(to right, rgba(102, 126, 234, 0.05), transparent);
        }
        
        .notification-item:hover::before {
            opacity: 1;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        /* Quick Action Buttons */
        .quick-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .quick-action-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(90deg);
        }
        
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }
        
        .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Modern Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-home"></i> <span class="d-none d-lg-inline">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item d-none d-md-inline-block">
                    <a href="{{ url('/') }}" target="_blank" class="nav-link">
                        <i class="fas fa-globe"></i> <span class="d-none d-lg-inline">View Site</span>
                    </a>
                </li>
            </ul>

            <!-- Dynamic Status Info -->
            <div class="header-status d-none d-xl-flex mx-3">
                <div>
                    <i class="far fa-clock"></i>
                    <span id="currentTime"></span>
                </div>
                <div>
                    <i class="far fa-calendar"></i>
                    <span id="currentDate"></span>
                </div>
            </div>

            <!-- Center Search -->
            <div class="navbar-search d-none d-lg-flex mx-auto">
                <input type="text" class="form-control" placeholder="Search content... (Ctrl+K)" id="globalSearch">
                <i class="fas fa-search"></i>
                <div id="searchResults" class="position-absolute bg-white border rounded shadow-lg mt-2" style="width: 500px; max-height: 400px; overflow-y: auto; display: none; z-index: 1000; left: 0; top: 100%;">
                    <!-- Results will be inserted here -->
                </div>
            </div>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto align-items-center">
                <!-- Notifications Dropdown -->
                <li class="nav-item dropdown notification-dropdown">
                    <a class="nav-link quick-action-btn" data-toggle="dropdown" href="#" role="button" title="Notifications">
                        <i class="far fa-bell" style="font-size: 1.1rem;"></i>
                        @if(isset($notificationCount) && $notificationCount > 0)
                            <span class="badge badge-warning navbar-badge">{{ $notificationCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">{{ $notificationCount ?? 0 }} Notifications</span>
                        @if(isset($pendingCommunities) && $pendingCommunities > 0)
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.communities.index') }}" class="notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 small"><strong>New Community Request</strong></p>
                                        <p class="mb-0 text-muted small">{{ $pendingCommunities }} pending approval{{ $pendingCommunities > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if(isset($unreadMessages) && $unreadMessages > 0)
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.contact-messages.index') }}" class="notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-envelope text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 small"><strong>New Messages</strong></p>
                                        <p class="mb-0 text-muted small">{{ $unreadMessages }} unread message{{ $unreadMessages > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if(!isset($notificationCount) || $notificationCount == 0)
                            <div class="dropdown-divider"></div>
                            <div class="notification-item text-center text-muted">
                                <p class="mb-0 small">No new notifications</p>
                            </div>
                        @endif
                        @if(isset($notificationCount) && $notificationCount > 0)
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">View All Notifications</a>
                        @endif
                    </div>
                </li>

                <!-- Quick Add Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link quick-action-btn" data-toggle="dropdown" href="#" role="button" title="Quick Add">
                        <i class="fas fa-plus-circle" style="font-size: 1.1rem;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('admin.news.create') }}" class="dropdown-item">
                            <i class="fas fa-newspaper me-2 text-primary"></i> Add News
                        </a>
                        <a href="{{ route('admin.events.create') }}" class="dropdown-item">
                            <i class="fas fa-calendar-alt me-2 text-danger"></i> Add Event
                        </a>
                        <a href="{{ route('admin.programs.create') }}" class="dropdown-item">
                            <i class="fas fa-graduation-cap me-2 text-warning"></i> Add Program
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.gallery.create') }}" class="dropdown-item">
                            <i class="fas fa-image me-2 text-info"></i> Add Gallery
                        </a>
                    </div>
                </li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link user-dropdown-toggle" href="#" id="navbarProfileDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info d-none d-lg-block">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header">
                            <strong>{{ auth()->user()->name }}</strong>
                            <div class="text-muted small">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                            <i class="fas fa-user-cog me-2"></i> Profile Settings
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-cog me-2"></i> Site Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                @php 
                    $adminLogo = \App\Models\Setting::get('logo');
                    $siteName = \App\Models\Setting::get('site_name') ?? 'IEYDA CMS';
                @endphp
                @if($adminLogo)
                    <img src="{{ Storage::url($adminLogo) }}" alt="{{ $siteName }}" class="brand-image">
                    <span class="brand-text">{{ $siteName }}</span>
                    <span class="brand-subtitle">Content Management</span>
                @else
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: white; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); border: 3px solid rgba(255, 255, 255, 0.3); z-index: 1;">
                        {{ strtoupper(substr($siteName, 0, 1)) }}
                    </div>
                    <span class="brand-text">{{ $siteName }}</span>
                    <span class="brand-subtitle">Content Management</span>
                @endif
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- Content Management -->
                        <li class="nav-header">CONTENT MANAGEMENT</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>News</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Events</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.programs.index') }}" class="nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>Programs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.drafts.index') }}" class="nav-link {{ request()->routeIs('admin.drafts.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Drafts & Scheduled</p>
                            </a>
                        </li>

                        <!-- Media & Visual -->
                        <li class="nav-header">MEDIA & VISUAL</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.hero-slides.index') }}" class="nav-link {{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>Hero Slides</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-image"></i>
                                <p>Gallery</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comment-dots"></i>
                                <p>Testimonials</p>
                            </a>
                        </li>

                        <!-- Pages & Static Content -->
                        <li class="nav-header">PAGES & STATIC</li>
                        <li class="nav-item has-treeview {{ request()->routeIs('admin.pages.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Pages
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages.edit', 'about') }}" class="nav-link {{ request()->routeIs('admin.pages.edit') && request()->route('slug') === 'about' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>About Page</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages.edit', 'contact') }}" class="nav-link {{ request()->routeIs('admin.pages.edit') && request()->route('slug') === 'contact' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Contact Page</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages.edit', 'privacy') }}" class="nav-link {{ request()->routeIs('admin.pages.edit') && request()->route('slug') === 'privacy' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Privacy Policy</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages.edit', 'terms') }}" class="nav-link {{ request()->routeIs('admin.pages.edit') && request()->route('slug') === 'terms' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Terms of Service</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Team & Community -->
                        <li class="nav-header">TEAM & COMMUNITY</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.team-members.index') }}" class="nav-link {{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Team Members</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.communities.index') }}" class="nav-link {{ request()->routeIs('admin.communities.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-network-wired"></i>
                                <p>Communities</p>
                            </a>
                        </li>

                        <!-- Communications -->
                        <li class="nav-header">COMMUNICATIONS</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>Financial Member Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Contact Messages</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.quran-competition.index') }}" class="nav-link {{ request()->routeIs('admin.quran-competition.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-trophy"></i>
                                <p>Qur'an Competition</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.ict-programme.index') }}" class="nav-link {{ request()->routeIs('admin.ict-programme.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-laptop"></i>
                                <p>ICT Programme</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.guest-registrations.index') }}" class="nav-link {{ request()->routeIs('admin.guest-registrations.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-plus"></i>
                                <p>Guest Registrations</p>
                            </a>
                        </li>

                        <!-- System -->
                        <li class="nav-header">SYSTEM</li>
                        @if(auth()->user()->canManageUsers())
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>User Management</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                            <small class="text-muted">@yield('page-subtitle', '')</small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert-modern alert-modern-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert-modern alert-modern-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ session('error') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert-modern alert-modern-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('warning') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="alert-modern alert-modern-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ session('info') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    
    <!-- jQuery (CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 Bundle (for AdminLTE compatibility) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Global search functionality - searches content across the system
            let searchTimeout;
            $('#globalSearch').on('keyup', function(e) {
                const searchTerm = $(this).val().trim();
                
                if (e.key === 'Enter' && searchTerm.length > 2) {
                    // Redirect to a search results page or news with search parameter
                    window.location.href = '{{ route("admin.news.index") }}?search=' + encodeURIComponent(searchTerm);
                }
                
                // Show search suggestions (optional - can be enhanced later)
                if (searchTerm.length > 0) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        // Filter sidebar menu for quick navigation
                        $('.nav-sidebar .nav-item').each(function() {
                            const text = $(this).find('.nav-link p').text().toLowerCase();
                            if (text.includes(searchTerm.toLowerCase())) {
                                $(this).show().addClass('search-highlight');
                            } else {
                                $(this).hide().removeClass('search-highlight');
                            }
                        });
                    }, 300);
                } else {
                    $('.nav-sidebar .nav-item').show().removeClass('search-highlight');
                }
            });
            
            // Clear search on escape
            $('#globalSearch').on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $(this).val('').blur();
                    $('.nav-sidebar .nav-item').show().removeClass('search-highlight');
                }
            });
            
            // Click outside search to clear filter
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.navbar-search').length) {
                    $('#globalSearch').val('');
                    $('.nav-sidebar .nav-item').show().removeClass('search-highlight');
                    $('#searchResults').hide();
                }
            });
            
            // Global Search with Ctrl+K
            $(document).keydown(function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    $('#globalSearch').focus();
                }
                if (e.key === 'Escape') {
                    $('#searchResults').hide();
                }
            });

            // Search functionality
            let searchTimeout;
            $('#globalSearch').on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val();
                const resultsDiv = $('#searchResults');

                if (query.length < 2) {
                    resultsDiv.hide();
                    return;
                }

                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("admin.search") }}',
                        type: 'GET',
                        data: { q: query },
                        success: function(response) {
                            if (response.results.length > 0) {
                                let html = '<div style="padding: 0.5rem;">';
                                html += '<small class="text-muted d-block mb-2">Found ' + response.count + ' results</small>';
                                response.results.forEach(function(item) {
                                    html += '<a href="' + item.url + '" class="d-block p-2 text-dark text-decoration-none" style="border-bottom: 1px solid #e9ecef; transition: all 0.2s;">';
                                    html += '<div class="d-flex align-items-center justify-content-between">';
                                    html += '<div>';
                                    html += '<i class="fas fa-' + item.icon + ' text-muted mr-2"></i>';
                                    html += '<strong>' + item.title.substring(0, 50) + '</strong>';
                                    html += '</div>';
                                    html += '<small class="text-muted">' + item.type + '</small>';
                                    html += '</div>';
                                    html += '</a>';
                                });
                                html += '</div>';
                                resultsDiv.html(html).show();
                            } else {
                                resultsDiv.html('<div class="p-3 text-center text-muted">No results found</div>').show();
                            }
                        }
                    });
                }, 300);
            });
            
            // Search highlight style
            if (!$('#search-highlight-style').length) {
                $('<style id="search-highlight-style">.search-highlight { background: rgba(102, 126, 234, 0.1); border-radius: 4px; }</style>').appendTo('head');
            }
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
            
            // Initialize all Bootstrap tooltips (optional enhancement)
            $('[data-toggle="tooltip"]').tooltip();
            
            // Dynamic Time & Date Display
            function updateDateTime() {
                const now = new Date();
                const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
                const dateOptions = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
                
                const timeElement = document.getElementById('currentTime');
                const dateElement = document.getElementById('currentDate');
                
                if (timeElement) {
                    timeElement.textContent = now.toLocaleTimeString('en-US', timeOptions);
                }
                if (dateElement) {
                    dateElement.textContent = now.toLocaleDateString('en-US', dateOptions);
                }
            }
            
            // Update time immediately and then every second
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
