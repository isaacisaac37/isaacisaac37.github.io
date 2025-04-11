<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_reservaciones.php");
        exit();
    } elseif ($_SESSION['role'] === 'usuario') {
        header("Location: reservacion.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['rol'];
            $_SESSION['nombre'] = $user['nombre'];

            if ($user['rol'] === 'admin') {
                header("Location: admin_reservaciones.php");
                exit();
            } elseif ($user['rol'] === 'usuario') {
                header("Location: reservacion.php");
                exit();
            }
        } else {
            $error = "Credenciales incorrectas";
        }
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabores & Lumbre - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #5d4037;
            --secondary: #8d6e63;
            --accent: #d7ccc8;
            --light: #efebe9;
            --dark: #3e2723;
            --gold: #ffd700;
        }
        
        body {
            background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Playfair Display', serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }
        
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            max-width: 450px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            border: 1px solid var(--accent);
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: all 0.5s ease;
        }
        
        .login-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        
        .restaurant-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .restaurant-logo h1 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .restaurant-logo .divider {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--gold));
            margin: 0 auto 1.5rem;
            border-radius: 3px;
        }
        
        .restaurant-logo p {
            color: var(--secondary);
            font-style: italic;
            margin-bottom: 0;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
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
        
        .input-group-text {
            background-color: var(--light);
            border: 1px solid var(--accent);
            color: var(--primary);
        }
        
        .btn-login {
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
        
        .btn-login:hover {
            background-color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .alert {
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .alert-danger {
            border-left-color: #c62828;
            background-color: rgba(198, 40, 40, 0.1);
        }
        
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--secondary);
        }
        
        .register-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border-bottom: 1px dashed var(--primary);
        }
        
        .register-link a:hover {
            color: var(--dark);
            border-bottom: 1px solid var(--dark);
        }
        
        .social-login {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--accent);
        }
        
        .social-login p {
            color: var(--secondary);
            margin-bottom: 1rem;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            transform: translateY(-3px);
        }
        
        .facebook {
            background-color: #3b5998;
        }
        
        .google {
            background-color: #db4437;
        }
        
        .twitter {
            background-color: #1da1f2;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-overlay"></div>
    <div class="container">
        <div class="login-container">
            <div class="restaurant-logo">
                <h1>Sabores & Lumbre</h1>
                <div class="divider"></div>
                <p>Experiencia gastronómica única</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class="register-link">
                ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
            </div>
            
            <div class="social-login">
                <p>O inicia sesión con</p>
                <div class="social-icons">
                    <a href="#" class="social-icon facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="#" class="social-icon twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de carga
        document.addEventListener('DOMContentLoaded', function() {
            const loginContainer = document.querySelector('.login-container');
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginContainer.style.opacity = '1';
                loginContainer.style.transform = 'translateY(0)';
                loginContainer.style.transition = 'all 0.5s ease';
            }, 100);
        });
    </script>
</body>
</html>