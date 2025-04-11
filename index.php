<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas</title>

    <!-- Enlace a Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            text-align: center;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
        }
        .card-text {
            font-size: 16px;
        }
        .card-footer {
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <header class="text-center my-4">
        <h1>Bienvenido al Sistema de Reservas</h1>
    </header>

    <!-- Menú tipo Mosaico -->
    <div class="container card-container">
        <!-- Card para Reservación -->
        <div class="card">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Reservación">
            <div class="card-body">
                <h5 class="card-title">Hacer una Reservación</h5>
                <p class="card-text">Realiza tu reservación en minutos.</p>
                <a href="reservacion.php" class="btn btn-primary">Ir a Reservar</a>
            </div>
            <div class="card-footer">
                <small>&copy; 2025 Restaurante</small>
            </div>
        </div>

        <!-- Card para Disponibilidad -->
        <div class="card">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Disponibilidad">
            <div class="card-body">
                <h5 class="card-title">Ver Disponibilidad</h5>
                <p class="card-text">Consulta la disponibilidad de mesas en tiempo real.</p>
                <a href="disponibilidad.php" class="btn btn-primary">Ver Disponibilidad</a>
            </div>
            <div class="card-footer">
                <small>&copy; 2025 Restaurante</small>
            </div>
        </div>

        <!-- Card para Administración (Solo si es admin) -->
        <?php if ($_SESSION['role'] === 'admin') : ?>
        <div class="card">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Administración">
            <div class="card-body">
                <h5 class="card-title">Administración</h5>
                <p class="card-text">Accede al panel de administración.</p>
                <a href="admin.php" class="btn btn-primary">Ir a Administración</a>
            </div>
            <div class="card-footer">
                <small>&copy; 2025 Restaurante</small>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Botón de Cerrar Sesión -->
    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>

    <!-- Pie de Página -->
    <footer class="text-center mt-5 p-3">
        <p>&copy; 2025 Restaurante</p>
    </footer>

</body>
</html>
