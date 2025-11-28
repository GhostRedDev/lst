<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPRL - @yield('title', 'Sistema Rosa María de Lira')</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --light-color: #ecf0f1;
            --white-color: #ffffff;
            --border-color: #dfe6e9;
            --text-color: #2d3436;
            --text-muted: #636e72;
            --shadow-light: 0 2px 15px rgba(0,0,0,0.08);
            --shadow-medium: 0 4px 20px rgba(0,0,0,0.12);
            --shadow-heavy: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* ===== NAVBAR ===== */
        .navbar-app {
            background: var(--white-color);
            box-shadow: var(--shadow-light);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-scrolled {
            padding: 0.75rem 0;
            box-shadow: var(--shadow-medium);
        }
        
        .navbar-brand-app {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .navbar-brand-app:hover {
            transform: translateY(-1px);
        }
        
        .brand-logo {
            color: var(--accent-color);
            font-size: 1.6rem;
        }
        
        .brand-text {
            font-weight: 600;
        }
        
        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
            margin-left: 5px;
        }
        
        /* Navigation Links */
        .nav-link-app {
            color: var(--secondary-color) !important;
            font-weight: 500;
            padding: 0.75rem 1.25rem !important;
            margin: 0 0.25rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-link-app:hover {
            color: var(--accent-color) !important;
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.08) 0%, rgba(231, 76, 60, 0.04) 100%);
            transform: translateY(-1px);
        }
        
        .nav-link-app.active {
            color: var(--accent-color) !important;
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.12) 0%, rgba(231, 76, 60, 0.06) 100%);
            font-weight: 600;
        }
        
        .nav-link-app.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent-color);
            border-radius: 0 2px 2px 0;
        }
        
        /* Dropdown Fixes */
        .nav-item.dropdown .nav-link-app {
            cursor: pointer;
        }
        
        .dropdown-toggle::after {
            margin-left: 0.5em;
            vertical-align: 0.15em;
        }
        
        /* User Dropdown */
        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white-color);
            font-weight: 600;
            font-size: 1rem;
            border: 3px solid var(--white-color);
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover .user-avatar {
            transform: scale(1.1);
            box-shadow: var(--shadow-medium);
        }
        
        .dropdown-menu-app {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-heavy);
            padding: 0.75rem;
            margin-top: 12px !important;
            border: 1px solid var(--border-color);
            min-width: 220px;
            background: rgba(255, 255, 255, 0.98);
        }
        
        .dropdown-item-app {
            padding: 0.875rem 1rem;
            border-radius: 10px;
            color: var(--text-color);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin: 2px 0;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        
        .dropdown-item-app:hover {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(231, 76, 60, 0.05) 100%);
            color: var(--accent-color);
            transform: translateX(5px);
        }
        
        .user-info-section {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: var(--accent-color);
            text-transform: capitalize;
            font-weight: 500;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content-app {
            min-height: calc(100vh - 140px);
            padding: 2rem 0;
            position: relative;
        }
        
        /* Enhanced Alerts */
        .alert-app {
            border: none;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 5px solid;
            box-shadow: var(--shadow-light);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.1) 0%, rgba(46, 204, 113, 0.05) 100%);
            border-left-color: var(--success-color);
            color: var(--success-color);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(231, 76, 60, 0.05) 100%);
            border-left-color: var(--accent-color);
            color: #c0392b;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, rgba(241, 196, 15, 0.1) 0%, rgba(241, 196, 15, 0.05) 100%);
            border-left-color: var(--warning-color);
            color: #d35400;
        }
        
        .alert-info {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(52, 152, 219, 0.05) 100%);
            border-left-color: var(--info-color);
            color: #2980b9;
        }
        
        /* Enhanced Buttons */
        .btn-app {
            padding: 0.875rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-app::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-app:hover::before {
            left: 100%;
        }
        
        .btn-app-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white-color);
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
        }
        
        .btn-app-primary:hover {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            color: var(--white-color);
        }
        
        .btn-app-outline {
            background: transparent;
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-app-outline:hover {
            background: var(--primary-color);
            color: var(--white-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 62, 80, 0.2);
        }
        
        /* Enhanced Cards */
        .card-app {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        
        .card-app:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }
        
        .card-header-sprl {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white-color);
            padding: 1.25rem 1.5rem;
            border-bottom: none;
            font-weight: 600;
        }
        
        .card-body-sprl {
            padding: 1.5rem;
        }
        
        /* Footer */
        .footer-app {
            background: var(--white-color);
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .developer-info {
            text-align: center;
        }
        
        .developer-name {
            font-weight: 600;
            color: var(--accent-color);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .developer-role {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        /* Form Enhancements */
        .form-control {
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.15);
            transform: translateY(-1px);
        }
        
        /* Animations */
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.5s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand-app {
                font-size: 1.2rem;
            }
            
            .brand-subtitle {
                display: none;
            }
            
            .main-content-app {
                padding: 1rem 0;
            }
            
            .nav-link-app {
                margin: 0.25rem 0;
                text-align: center;
                justify-content: center;
            }
            
            .nav-link-app.active::before {
                display: none;
            }
            
            .dropdown-menu-app {
                margin-top: 8px !important;
                min-width: 200px;
            }
            
            .card-app {
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand-app {
                font-size: 1.1rem;
            }
            
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .btn-app {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .alert-app {
                padding: 1rem 1.25rem;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #c0392b, var(--accent-color));
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-app sticky-top">
            <div class="container">
                <!-- Brand Logo & Name -->
                <a class="navbar-brand-app" href="{{ url('/') }}">
                    <i class="fas fa-hospital-alt brand-logo"></i>
                    <span class="brand-text">SPRL</span>
                    <span class="brand-subtitle">Sistema Médico</span>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Content -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Left Navigation -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link-app {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="fas fa-home"></i>
                                    <span>Inicio</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link-app {{ Request::is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                                    <i class="fas fa-users"></i>
                                    <span>Pacientes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link-app {{ Request::is('reports*') ? 'active' : '' }}" href="#">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Reportes</span>
                                </a>
                            </li>
                             
                            <li class="nav-item dropdown">
                                <a class="nav-link-app dropdown-toggle {{ Request::is('locations*') ? 'active' : '' }}" 
                                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Ubicaciones</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-app">
                                    <li>
                                        <a class="dropdown-item-app {{ Request::is('locations') ? 'active' : '' }}" 
                                           href="{{ route('locations.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item-app {{ Request::is('locations/states*') ? 'active' : '' }}" 
                                           href="{{ route('locations.states.index') }}">
                                            <i class="fas fa-globe-americas"></i>
                                            Estados
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-app {{ Request::is('locations/municipalities*') ? 'active' : '' }}" 
                                           href="{{ route('locations.municipalities.index') }}">
                                            <i class="fas fa-map"></i>
                                            Municipios
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-app {{ Request::is('locations/health-centers*') ? 'active' : '' }}" 
                                           href="{{ route('locations.health-centers.index') }}">
                                            <i class="fas fa-hospital"></i>
                                            Centros de Salud
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-app {{ Request::is('locations/communities*') ? 'active' : '' }}" 
                                           href="{{ route('locations.communities.index') }}">
                                            <i class="fas fa-building"></i>
                                            Comunidades
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Navigation -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <!-- Guest Links -->
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link-app" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Ingresar</span>
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link-app" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Registrarse</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- User Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-app">
                                    <!-- User Information -->
                                    <li>
                                        <div class="user-info-section">
                                            <div class="user-name">{{ Auth::user()->name }}</div>
                                            <div class="user-role">
                                                <i class="fas fa-user-tag me-1"></i>
                                                {{ Auth::user()->role ?? 'Usuario' }}
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-app" href="#">
                                            <i class="fas fa-user-circle"></i>
                                            Mi Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-app" href="#">
                                            <i class="fas fa-cog"></i>
                                            Configuración
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item-app text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Cerrar Sesión
                                        </a>
                                    </li>
                                </ul>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content-app">
            <div class="container fade-in">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-app alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-app alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-app alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Advertencia!</strong> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-app alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información!</strong> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-app alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Errores encontrados:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Header Section -->
                @hasSection('page-header')
                    <div class="row mb-4">
                        <div class="col-12">
                            @yield('page-header')
                        </div>
                    </div>
                @endif

                <!-- Dynamic Content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer-app">
            <div class="container">
                <div class="developer-info">
                    <div class="developer-name">
                        Petter Franco
                    </div>
                    <div class="developer-role">
                        Estudiante de Medicina Integral Comunitaria - Sistema Rosa María de Lira
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    
    <script>
        // Enhanced Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-app');
            if (window.scrollY > 20) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Enhanced active navigation highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link-app');
            
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (linkPath && currentPath.startsWith(linkPath) && linkPath !== '/') {
                    link.classList.add('active');
                } else if (linkPath === currentPath) {
                    link.classList.add('active');
                }
            });

            // Enhanced auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 6000);
            });

            // Add loading states to buttons
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="loading-spinner"></span> Procesando...';
                    }
                });
            });

            // Initialize dropdowns properly
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                // Remove any custom animations that might interfere
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.opacity = '1';
                    menu.style.transform = 'none';
                    menu.style.transition = 'none';
                }
            });
        });

        // Simple dropdown hover effect (optional)
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            dropdown.addEventListener('mouseenter', function() {
                if (window.innerWidth > 768) { // Only on desktop
                    const bsDropdown = new bootstrap.Dropdown(this.querySelector('.dropdown-toggle'));
                    bsDropdown.show();
                }
            });
            
            dropdown.addEventListener('mouseleave', function() {
                if (window.innerWidth > 768) { // Only on desktop
                    const bsDropdown = new bootstrap.Dropdown(this.querySelector('.dropdown-toggle'));
                    bsDropdown.hide();
                }
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-app').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple-effect');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple effect
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .ripple-effect {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
            }
            
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .btn-app {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(rippleStyle);

        // Fix for mobile dropdowns
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(menu => {
                    if (!menu.parentElement.contains(e.target)) {
                        const bsDropdown = bootstrap.Dropdown.getInstance(menu.previousElementSibling);
                        if (bsDropdown) {
                            bsDropdown.hide();
                        }
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>