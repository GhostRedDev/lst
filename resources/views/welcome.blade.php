<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPRL - Sistema Rosa María de Lira</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-black: #1a1a1a;
            --secondary-black: #2d2d2d;
            --accent-black: #404040;
            --pure-white: #ffffff;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --accent-color: #e74c3c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--pure-white);
            color: var(--primary-black);
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--pure-white) 0%, var(--light-gray) 100%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        
        .hero-bg-pattern {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(231, 76, 60, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(231, 76, 60, 0.03) 0%, transparent 50%);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .system-acronym {
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-color);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 1rem;
            animation: fadeInDown 1s ease-out;
        }
        
        .system-name {
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary-black);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        
        .system-subtitle {
            font-size: 1.5rem;
            color: var(--accent-black);
            font-weight: 400;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        
        .system-description {
            font-size: 1.1rem;
            color: var(--secondary-black);
            line-height: 1.7;
            margin-bottom: 3rem;
            max-width: 600px;
            animation: fadeInUp 1s ease-out 0.9s both;
        }
        
        .stats-container {
            display: flex;
            gap: 3rem;
            margin-bottom: 3rem;
            animation: fadeInUp 1s ease-out 1.2s both;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-color);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--accent-black);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .auth-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 1.5s both;
        }
        
        .btn-sprl {
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-sprl-primary {
            background: var(--primary-black);
            color: var(--pure-white);
            border-color: var(--primary-black);
        }
        
        .btn-sprl-primary:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
            color: var(--pure-white);
        }
        
        .btn-sprl-outline {
            background: transparent;
            color: var(--primary-black);
            border-color: var(--primary-black);
        }
        
        .btn-sprl-outline:hover {
            background: var(--primary-black);
            color: var(--pure-white);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(26, 26, 26, 0.2);
        }
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: var(--light-gray);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: var(--primary-black);
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            text-align: center;
            color: var(--accent-black);
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            background: var(--pure-white);
            padding: 3rem 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid var(--medium-gray);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--accent-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-black);
            margin-bottom: 1rem;
        }
        
        .feature-description {
            color: var(--accent-black);
            line-height: 1.6;
        }
        
        /* Footer */
        .footer {
            background: var(--primary-black);
            color: var(--pure-white);
            padding: 4rem 0 2rem;
        }
        
        .developer-info {
            text-align: center;
            padding: 2rem 0;
            border-top: 1px solid var(--accent-black);
            margin-top: 2rem;
        }
        
        .developer-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 0.5rem;
        }
        
        .developer-role {
            color: var(--medium-gray);
            font-size: 0.9rem;
        }
        
        /* Animations */
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
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .system-name {
                font-size: 2.5rem;
            }
            
            .system-subtitle {
                font-size: 1.2rem;
            }
            
            .stats-container {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .auth-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-sprl {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            .hero-bg-pattern {
                width: 100%;
                opacity: 0.3;
            }
        }
        
        @media (max-width: 576px) {
            .system-name {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .feature-card {
                padding: 2rem 1.5rem;
            }
        }
        
        /* Floating Elements */
        .floating-element {
            position: absolute;
            background: var(--accent-color);
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .float-1 {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .float-2 {
            width: 150px;
            height: 150px;
            bottom: 20%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .float-3 {
            width: 80px;
            height: 80px;
            top: 60%;
            left: 5%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <!-- Floating Background Elements -->
        <div class="floating-element float-1"></div>
        <div class="floating-element float-2"></div>
        <div class="floating-element float-3"></div>
        
        <!-- Background Pattern -->
        <div class="hero-bg-pattern"></div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <!-- System Acronym -->
                        <div class="system-acronym">
                            SPRL
                        </div>
                        
                        <!-- System Name -->
                        <h1 class="system-name">
                            Sistema Rosa María de Lira
                        </h1>
                        
                        <!-- Subtitle -->
                        <p class="system-subtitle">
                            Plataforma integral de gestión médica y contabilidad de pacientes
                        </p>
                        
                        <!-- Description -->
                        <p class="system-description">
                            Sistema especializado para el registro completo de pacientes, control médico, 
                            seguimiento de tratamientos, exportación de datos y gestión administrativa 
                            en instituciones de salud.
                        </p>
                        
                        <!-- Live Statistics -->
                        <div class="stats-container">
                            <div class="stat-item">
                                <div class="stat-number" id="patientsCount">0</div>
                                <div class="stat-label">Pacientes Registrados</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="consultationsCount">0</div>
                                <div class="stat-label">Consultas Realizadas</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Datos Seguros</div>
                            </div>
                        </div>
                        
                        <!-- Authentication Buttons -->
                        <div class="auth-buttons">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/home') }}" class="btn-sprl btn-sprl-primary pulse-animation">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Ir al Dashboard
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-sprl btn-sprl-outline">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn-sprl btn-sprl-primary">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Iniciar Sesión
                                    </a>
                                    
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn-sprl btn-sprl-outline">
                                            <i class="fas fa-user-plus"></i>
                                            Crear Cuenta
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">Funcionalidades Principales</h2>
            <p class="section-subtitle">
                Descubre todas las herramientas profesionales que SPRL ofrece para la gestión médica integral
            </p>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Gestión de Pacientes</h3>
                    <p class="feature-description">
                        Registro completo de datos médicos, historial clínico, información personal 
                        y seguimiento detallado de cada paciente en el sistema.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="feature-title">Contabilidad Médica</h3>
                    <p class="feature-description">
                        Control financiero completo: facturación, pagos, estados de cuenta 
                        y reportes económicos integrados con la historia clínica.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <h3 class="feature-title">Exportación a Excel</h3>
                    <p class="feature-description">
                        Genera reportes detallados y exporta toda la información médica 
                        y contable a Excel para análisis y presentaciones profesionales.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="feature-title">Búsqueda Avanzada</h3>
                    <p class="feature-description">
                        Encuentra rápidamente pacientes, diagnósticos, tratamientos 
                        y registros médicos con filtros inteligentes y búsqueda predictiva.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Agenda Médica</h3>
                    <p class="feature-description">
                        Sistema de citas integrado con recordatorios automáticos, 
                        control de disponibilidad y gestión de horarios médicos.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Reportes y Estadísticas</h3>
                    <p class="feature-description">
                        Dashboard interactivo con gráficos, estadísticas médicas 
                        y métricas de desempeño en tiempo real para toma de decisiones.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="developer-info">
                        <div class="developer-name">
                            Petter Franco
                        </div>
                        <div class="developer-role">
                            Estudiante de Primer Año de Medicina Integral Comunitaria
                        </div>
                        <div class="developer-role mt-2">
                            Proyecto en desarrollo - Sistema Rosa María de Lira (SPRL)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animated Counter for Statistics
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = Math.floor(target).toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start).toLocaleString();
                }
            }, 16);
        }

        // Initialize counters when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate API call to get real data
            // In a real application, you would fetch this from your backend
            setTimeout(() => {
                const patientsCount = {{ $totalPatients ?? 0 }};
                
                animateCounter(document.getElementById('patientsCount'), patientsCount);
                animateCounter(document.getElementById('consultationsCount'), consultationsCount);
            }, 1000);

            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe feature cards
            document.querySelectorAll('.feature-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });

            // Floating elements animation
            const floatingElements = document.querySelectorAll('.floating-element');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 2}s`;
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>