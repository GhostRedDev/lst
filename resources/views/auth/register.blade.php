<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SPRL</title>
    
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
        
        .register-container {
            width: 100%;
            max-width: 500px;
        }
        
        .register-card {
            background: var(--pure-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid var(--medium-gray);
            position: relative;
            overflow: hidden;
        }
        
        .register-card::before {
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
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }
        
        .strength-bar {
            height: 4px;
            background: var(--medium-gray);
            border-radius: 2px;
            margin-top: 0.25rem;
            overflow: hidden;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-weak {
            background: #e74c3c;
            width: 33%;
        }
        
        .strength-medium {
            background: #f39c12;
            width: 66%;
        }
        
        .strength-strong {
            background: #27ae60;
            width: 100%;
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
        
        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--medium-gray);
        }
        
        .login-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
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
        
        .role-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 0.5rem;
        }
        
        .role-option {
            border: 2px solid var(--medium-gray);
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .role-option:hover {
            border-color: var(--accent-color);
        }
        
        .role-option.selected {
            border-color: var(--accent-color);
            background: rgba(231, 76, 60, 0.05);
        }
        
        .role-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--accent-black);
        }
        
        .role-name {
            font-size: 0.8rem;
            font-weight: 500;
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
            .register-card {
                padding: 2rem 1.5rem;
            }
            
            .role-selection {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-element float-1"></div>
    <div class="floating-element float-2"></div>
    
    <div class="register-container">
        <div class="register-card">
            <!-- System Header -->
            <div class="system-header">
                <div class="system-acronym">SPRL</div>
                <h2 class="system-name">Sistema Rosa María de Lira</h2>
                <p class="system-subtitle">Crear Cuenta</p>
            </div>
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Ingresa tu nombre completo">
                    </div>
                </div>
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="usuario@ejemplo.com">
                    </div>
                </div>
                
                <!-- Role Selection -->
                <div class="form-group">
                    <label class="form-label">Tipo de Usuario</label>
                    <div class="role-selection">
                        <div class="role-option" data-role="medico">
                            <div class="role-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="role-name">Médico</div>
                        </div>
                        <div class="role-option" data-role="administrativo">
                            <div class="role-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="role-name">Administrativo</div>
                        </div>
                        <div class="role-option" data-role="estudiante">
                            <div class="role-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="role-name">Estudiante</div>
                        </div>
                    </div>
                    <input type="hidden" name="role" id="role" value="{{ old('role', 'medico') }}" required>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Mínimo 8 caracteres">
                    </div>
                    <div class="password-strength">
                        <div>Seguridad: <span id="strength-text">Débil</span></div>
                        <div class="strength-bar">
                            <div class="strength-fill strength-weak" id="strength-fill"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Repite tu contraseña">
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-sprl btn-sprl-primary">
                    <i class="fas fa-user-plus"></i>
                    Crear Cuenta
                </button>
            </form>
            
            <!-- Login Link -->
            <div class="login-link">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}">Inicia sesión aquí</a>
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
            // Role Selection
            const roleOptions = document.querySelectorAll('.role-option');
            const roleInput = document.getElementById('role');
            
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    roleOptions.forEach(opt => opt.classList.remove('selected'));
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Update hidden input value
                    roleInput.value = this.dataset.role;
                });
            });
            
            // Select first role by default
            if (roleOptions.length > 0) {
                roleOptions[0].classList.add('selected');
            }
            
            // Password Strength Checker
            const passwordInput = document.getElementById('password');
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                // Length check
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                
                // Character variety checks
                if (/[a-z]/.test(password)) strength += 1;
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
                
                // Update strength indicator
                strengthFill.className = 'strength-fill';
                
                if (password.length === 0) {
                    strengthText.textContent = 'Débil';
                    strengthFill.classList.add('strength-weak');
                } else if (strength <= 2) {
                    strengthText.textContent = 'Débil';
                    strengthFill.classList.add('strength-weak');
                } else if (strength <= 4) {
                    strengthText.textContent = 'Media';
                    strengthFill.classList.add('strength-medium');
                } else {
                    strengthText.textContent = 'Fuerte';
                    strengthFill.classList.add('strength-strong');
                }
            });
            
            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden. Por favor, verifica.');
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres.');
                    return false;
                }
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