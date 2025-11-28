<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SPRL</title>
    
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
            background: linear-gradient(135deg, var(--pure-white) 0%, var(--light-gray) 100%);
            color: var(--primary-black);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: var(--pure-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid var(--medium-gray);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--accent-color);
        }
        
        .system-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .system-acronym {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--accent-color);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        
        .system-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-black);
            margin-bottom: 0.5rem;
        }
        
        .system-subtitle {
            font-size: 0.9rem;
            color: var(--accent-black);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--primary-black);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--medium-gray);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--pure-white);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-black);
        }
        
        .input-with-icon .form-control {
            padding-left: 45px;
        }
        
        .btn-sprl {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-sprl-primary {
            background: var(--primary-black);
            color: var(--pure-white);
        }
        
        .btn-sprl-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
        }
        
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember-me input {
            width: 16px;
            height: 16px;
        }
        
        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--medium-gray);
        }
        
        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }
        
        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            border-color: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }
        
        .floating-element {
            position: absolute;
            background: var(--accent-color);
            border-radius: 50%;
            opacity: 0.05;
            animation: float 6s ease-in-out infinite;
        }
        
        .float-1 {
            width: 80px;
            height: 80px;
            top: -40px;
            right: -40px;
        }
        
        .float-2 {
            width: 60px;
            height: 60px;
            bottom: -30px;
            left: -30px;
            animation-delay: 2s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(180deg);
            }
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .login-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-element float-1"></div>
    <div class="floating-element float-2"></div>
    
    <div class="login-container">
        <div class="login-card">
            <!-- System Header -->
            <div class="system-header">
                <div class="system-acronym">SPRL</div>
                <h2 class="system-name">Sistema Rosa María de Lira</h2>
                <p class="system-subtitle">Iniciar Sesión</p>
            </div>
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="usuario@ejemplo.com">
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Ingresa tu contraseña">
                    </div>
                </div>
                
                <!-- Login Options -->
                <div class="login-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Recordarme
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-sprl btn-sprl-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>
            
            <!-- Register Link -->
            <div class="register-link">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}">Regístrate aquí</a>
            </div>
        </div>
        
        <!-- Developer Info -->
        <div class="text-center mt-4">
            <small class="text-muted">
                Desarrollado por Petter Franco - Estudiante de Medicina Integral Comunitaria
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
            
            // Floating elements animation
            const floatingElements = document.querySelectorAll('.floating-element');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 2}s`;
            });
        });
    </script>
</body>
</html>