<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Admission System') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Admin Dashboard CSS -->
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 0;
            transition: all 0.15s ease-in-out;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link i {
            width: 1.5rem;
            text-align: center;
        }

        .content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-navbar {
            height: var(--header-height);
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid var(--danger-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .text-gray-600 {
            color: #858796 !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }

        .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .chart-area {
            position: relative;
            height: 20rem;
        }

        .chart-pie {
            position: relative;
            height: 15rem;
        }

        .table th {
            background-color: #f8f9fc;
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
        }

        .btn {
            border-radius: 0.35rem;
            font-weight: 400;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .mobile-sidebar-toggle {
                display: block !important;
            }
        }

        .mobile-sidebar-toggle {
            display: none;
        }

        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            color: #fff;
            font-weight: 600;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 1rem 0;
        }

        .sidebar-heading {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            padding: 0.5rem 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap mr-2"></i>
            Admission Admin
        </div>

        <hr class="sidebar-divider">

        <!-- Admin Authentication & Dashboard -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" 
                   href="#adminDashboardCollapse" 
                   data-bs-toggle="collapse" 
                   aria-expanded="{{ request()->routeIs('admin.dashboard*') ? 'true' : 'false' }}" 
                   aria-controls="adminDashboardCollapse">
                    <span>
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Admin Authentication & Dashboard
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.dashboard*') ? 'show' : '' }}" id="adminDashboardCollapse">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') && !request()->has(['stats', 'class', 'monthly', 'recent']) ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Dashboard Statistics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') && request()->has('stats') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard', ['stats' => 'quick']) }}">
                                <i class="fas fa-bolt mr-2"></i>
                                Quick Stats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') && request()->has('class') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard', ['class' => 'all']) }}">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Class-wise Stats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') && request()->has('monthly') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard', ['monthly' => 'trends']) }}">
                                <i class="fas fa-chart-line mr-2"></i>
                                Monthly Trends
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') && request()->has('recent') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard', ['recent' => 'applications']) }}">
                                <i class="fas fa-clock mr-2"></i>
                                Recent Applications
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">📋 Admission Management</div>

        <!-- Admission Sessions -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Admission Sessions feature coming soon!')">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Admission Sessions
                </a>
            </li>
        </ul>

        <!-- Applications -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.applications*') ? 'active' : '' }}" 
                   href="{{ route('admin.applications') }}">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    All Applications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.applications', ['status' => 'pending']) }}">
                    <i class="fas fa-clock mr-2"></i>
                    Pending Review
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.applications', ['status' => 'approved']) }}">
                    <i class="fas fa-check-circle mr-2"></i>
                    Approved
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.applications', ['status' => 'rejected']) }}">
                    <i class="fas fa-times-circle mr-2"></i>
                    Rejected
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">🛠️ Form & Setup</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Form Builder feature coming soon!')">
                    <i class="fas fa-wpforms mr-2"></i>
                    Form Builder
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Eligibility Criteria feature coming soon!')">
                    <i class="fas fa-user-check mr-2"></i>
                    Eligibility Criteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Document Management feature coming soon!')">
                    <i class="fas fa-folder-open mr-2"></i>
                    Document Management
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">🏆 Merit & Selection</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Merit List feature coming soon!')">
                    <i class="fas fa-trophy mr-2"></i>
                    Merit List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Result Publishing feature coming soon!')">
                    <i class="fas fa-bullhorn mr-2"></i>
                    Publish Results
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">💰 Payments</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Payment Management feature coming soon!')">
                    <i class="fas fa-credit-card mr-2"></i>
                    Payment Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Fee Setup feature coming soon!')">
                    <i class="fas fa-dollar-sign mr-2"></i>
                    Fee Setup
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">📢 Communication</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Notification System coming soon!')">
                    <i class="fas fa-bell mr-2"></i>
                    Send Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Bulk Communication feature coming soon!')">
                    <i class="fas fa-envelope-bulk mr-2"></i>
                    Bulk Communication
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">📊 Reports & Export</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.applications.export') }}">
                    <i class="fas fa-download mr-2"></i>
                    Export Applications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Advanced Reports coming soon!')">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Advanced Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Bulk admit card generation available in Applications list')">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Bulk Admit Cards
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">👥 Users & Roles</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('User management coming soon!')">
                    <i class="fas fa-users-cog mr-2"></i>
                    Admin Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Role management coming soon!')">
                    <i class="fas fa-user-shield mr-2"></i>
                    Roles & Permissions
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">⚙️ Settings</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('System settings coming soon!')">
                    <i class="fas fa-cog mr-2"></i>
                    System Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Institute info settings coming soon!')">
                    <i class="fas fa-university mr-2"></i>
                    Institute Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Gateway settings coming soon!')">
                    <i class="fas fa-plug mr-2"></i>
                    SMS/Email Gateway
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">❓ Help & Support</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Help system coming soon!')">
                    <i class="fas fa-question-circle mr-2"></i>
                    Help & FAQ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('User manual coming soon!')">
                    <i class="fas fa-book mr-2"></i>
                    User Manual
                </a>
            </li>
        </ul>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light top-navbar">
            <div class="container-fluid">
                <button class="btn btn-link mobile-sidebar-toggle d-md-none" 
                        onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="navbar-nav ms-auto">
                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger badge-counter">3+</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">New application received</a></li>
                            <li><a class="dropdown-item" href="#">Document verification needed</a></li>
                            <li><a class="dropdown-item" href="#">Weekly report ready</a></li>
                        </ul>
                    </div>

                    <!-- User Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" 
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="user-avatar me-2" 
                                 src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=4e73df&color=fff" 
                                 alt="User Avatar">
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-sidebar-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
