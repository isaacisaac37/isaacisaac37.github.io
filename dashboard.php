<?php
session_start();
include 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$reservas_usuario = [];

// Consulta las reservas del usuario
$sql = "SELECT * FROM reservaciones WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()){
    $reservas_usuario[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Mis Reservas</h2>
    <?php if(empty($reservas_usuario)): ?>
        <div class="alert alert-info">No tienes reservas.</div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Personas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas_usuario as $reserva): ?>
                    <tr>
                        <td><?php echo $reserva['id']; ?></td>
                        <td><?php echo $reserva['nombre']; ?></td>
                        <td><?php echo $reserva['fecha']; ?></td>
                        <td><?php echo $reserva['hora']; ?></td>
                        <td><?php echo $reserva['personas']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="reservacion.php" class="btn btn-primary">Hacer Otra Reservación</a>
    <br>
    <a href="logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
</div>
</body>
</html>
