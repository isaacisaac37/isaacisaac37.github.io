<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "El correo electrónico ya está registrado";
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'usuario')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $nombre, $email, $passwordHash);
        if ($stmt->execute()) {
            $success = "Registro exitoso. Puedes iniciar sesión ahora.";
        } else {
            $error = "Hubo un error en el registro. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sabores & Lumbre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #5d4037;
            --secondary: #8d6e63;
            --accent: #d7ccc8;
            --light: #efebe9;
            --dark: #3e2723;
            --success: #2e7d32;
            --danger: #c62828;
        }
        
        body {
            background: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }
        
        .register-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            margin: 2rem;
            position: relative;
            z-index: 1;
            border: 1px solid var(--accent);
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .restaurant-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .restaurant-logo h2 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        
        .restaurant-logo .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            margin: 0 auto 1.5rem;
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid var(--accent);
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(93, 64, 55, 0.25);
        }
        
        .btn-register {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s;
            width: 100%;
            color: white;
            margin-top: 1rem;
        }
        
        .btn-register:hover {
            background-color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .alert {
            border-radius: 8px;
            border-left: 4px solid;
            padding: 1rem;
        }
        
        .alert-success {
            border-left-color: var(--success);
            background-color: rgba(46, 125, 50, 0.1);
            color: var(--success);
        }
        
        .alert-danger {
            border-left-color: var(--danger);
            background-color: rgba(198, 40, 40, 0.1);
            color: var(--danger);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--secondary);
        }
        
        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border-bottom: 1px dashed var(--primary);
        }
        
        .login-link a:hover {
            color: var(--dark);
            border-bottom: 1px solid var(--dark);
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary);
        }
        
        .input-group {
            position: relative;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <div class="restaurant-logo">
            <h2>Sabores & Lumbre</h2>
            <div class="divider"></div>
            <p>Únete a nuestra experiencia gastronómica</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                <a href='login.php' class='btn btn-register mt-3'>
                    <i class="fas fa-sign-in-alt me-2"></i>Ir al inicio de sesión
                </a>
            </div>
        <?php else: ?>
            <form action="register.php" method="POST">
                <div class="mb-4">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-user"></i> Nombre Completo
                    </label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-4">
                    <div class="input-group">
                        <label for="password" class="form-label w-100">
                            <i class="fas fa-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="far fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i>Registrarse
                </button>
            </form>
            
            <div class="login-link">
                ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Efecto de carga
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.register-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
                container.style.transition = 'all 0.5s ease';
            }, 100);
        });
    </script>
</body>
</html>