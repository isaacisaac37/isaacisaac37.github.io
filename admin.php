<?php
session_start();
include 'config.php';

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}



$reservaciones = [];
$sql = "SELECT * FROM reservaciones";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservaciones[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Panel de Administración</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservaciones as $reserva): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['id']) ?></td>
                        <td><?= htmlspecialchars($reserva['nombre']) ?></td>
                        <td><?= htmlspecialchars($reserva['fecha']) ?></td>
                        <td><?= htmlspecialchars($reserva['hora']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($reserva['estado'])) ?></td>
                        <td>
                            <a href="modificar_reserva.php?id=<?= $reserva['id'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                            <a href="eliminar_reserva.php?id=<?= $reserva['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta reserva?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
