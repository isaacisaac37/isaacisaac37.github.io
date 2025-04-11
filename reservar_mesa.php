<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['mesa_id'], $_GET['fecha'], $_GET['hora'])) {
    $mesa_id = $_GET['mesa_id'];
    $fecha = $_GET['fecha'];
    $hora = $_GET['hora'];
    $user_id = $_SESSION['user_id'];

    // Insertar la reservación
    $sql = "INSERT INTO reservaciones (mesa_id, fecha, hora, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issi', $mesa_id, $fecha, $hora, $user_id);

    if ($stmt->execute()) {
        // Marcar la mesa como "ocupada"
        $sql_update = "UPDATE mesas SET estado = 'ocupada' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('i', $mesa_id);
        $stmt_update->execute();

        $success = "Reservación realizada con éxito.";
    } else {
        $error = "Error al realizar la reservación.";
    }
} else {
    $error = "No se proporcionaron datos válidos.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservación Confirmada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #5d4037;
            --secondary: #8d6e63;
            --accent: #d7ccc8;
            --light: #efebe9;
            --dark: #3e2723;
            --success: #4caf50;
            --danger: #f44336;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }
        
        .container {
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            border: 1px solid var(--accent);
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        h2 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }
        
        .alert {
            border-radius: 10px;
            border-left: 5px solid;
            padding: 20px;
            font-size: 1.1rem;
        }
        
        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            border-left-color: var(--success);
            color: var(--success);
        }
        
        .alert-danger {
            background-color: rgba(244, 67, 54, 0.1);
            border-left-color: var(--danger);
            color: var(--danger);
        }
        
        .btn {
            border-radius: 10px;
            font-size: 1.1rem;
            padding: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--dark);
            border-color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: var(--dark);
            border-color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .confirmation-icon {
            font-size: 5rem;
            color: var(--success);
            margin-bottom: 20px;
            text-align: center;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php if (isset($success)): ?>
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        <?php endif; ?>
        
        <h2>Confirmación de Reservación</h2>
        
        <?php if (isset($error)): ?>
            <div class='alert alert-danger'>
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class='alert alert-success'>
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            </div>
            
            <a href='ver_reservaciones.php' class='btn btn-primary w-100'>
                <i class="fas fa-calendar-alt"></i> Ver Mis Reservaciones
            </a>
        <?php endif; ?>
        
        <a href="reservacion.php" class="btn btn-secondary w-100">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>