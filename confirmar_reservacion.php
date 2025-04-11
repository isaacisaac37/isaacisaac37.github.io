<?php
session_start();
include 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se recibió el ID de la reservación
if (isset($_GET['id'])) {
    $reserva_id = $_GET['id'];

    // Actualizar el estado a "confirmada"
    $sql = "UPDATE reservaciones SET estado = 'confirmada' WHERE id = ? AND estado = 'pendiente'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reserva_id);
    
    if ($stmt->execute()) {
        header("Location: ver_reservaciones.php?success=Reservación confirmada");
    } else {
        header("Location: ver_reservaciones.php?error=No se pudo confirmar la reservación");
    }
} else {
    header("Location: ver_reservaciones.php?error=ID de reservación inválido");
}
exit();
