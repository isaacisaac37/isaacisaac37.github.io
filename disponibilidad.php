<?php
session_start();
include 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}

// Verificar si se recibió la fecha y la hora del formulario
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$hora = isset($_POST['hora']) ? $_POST['hora'] : '';

// Si no se ha enviado la fecha o la hora, redirigir
if (empty($fecha) || empty($hora)) {
    echo "Por favor, seleccione una fecha y hora.";
    exit();
}

// Consulta SQL para obtener las mesas disponibles
$sql = "SELECT * FROM mesas WHERE id NOT IN (SELECT mesa_id FROM reservaciones WHERE fecha = ? AND hora = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $fecha, $hora);
$stmt->execute();
$result = $stmt->get_result();

// Mostrar las mesas disponibles
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas Disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Mesas Disponibles para la Fecha y Hora Seleccionadas</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Mesa</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>
                                <!-- Enlace para reservar la mesa -->
                                <a href="reservar_mesa.php?mesa_id=<?php echo $row['id']; ?>&fecha=<?php echo $fecha; ?>&hora=<?php echo $hora; ?>" class="btn btn-primary">Reservar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No hay mesas disponibles para la fecha y hora seleccionadas.</p>
        <?php endif; ?>
    </div>
</body>
</html>
