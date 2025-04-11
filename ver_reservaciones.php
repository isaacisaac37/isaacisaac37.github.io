<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.id, m.descripcion AS mesa, r.fecha, r.hora, r.estado 
        FROM reservaciones r
        INNER JOIN mesas m ON r.mesa_id = m.id
        WHERE r.user_id = ?
        ORDER BY r.fecha DESC, r.hora DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservaciones</title>
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
            --warning: #ff8f00;
            --danger: #c62828;
            --info: #0288d1;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Roboto', sans-serif;
            background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
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
            max-width: 1000px;
            background-color: rgba(255, 255, 255, 0.97);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            margin-top: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--accent);
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--accent);
        }
        
        h2 {
            color: var(--primary);
            font-weight: 700;
            margin: 0;
            font-size: 2rem;
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
        }
        
        .btn-danger:hover {
            background-color: #b71c1c;
            border-color: #b71c1c;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--dark);
            border-color: var(--dark);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e65100;
            border-color: #e65100;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }
        
        .alert {
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .alert-success {
            border-left-color: var(--success);
            background-color: rgba(46, 125, 50, 0.1);
        }
        
        .alert-danger {
            border-left-color: var(--danger);
            background-color: rgba(198, 40, 40, 0.1);
        }
        
        .alert-info {
            border-left-color: var(--info);
            background-color: rgba(2, 136, 209, 0.1);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 2rem;
            background-color: white;
        }
        
        .table thead th {
            background-color: var(--primary);
            color: white;
            border-bottom: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(141, 110, 99, 0.05);
        }
        
        .table td, .table th {
            vertical-align: middle;
            padding: 1rem;
        }
        
        .badge {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 50px;
            text-transform: uppercase;
        }
        
        .bg-success {
            background-color: rgba(46, 125, 50, 0.1) !important;
            color: var(--success) !important;
        }
        
        .bg-warning {
            background-color: rgba(255, 143, 0, 0.1) !important;
            color: var(--warning) !important;
        }
        
        .bg-danger {
            background-color: rgba(198, 40, 40, 0.1) !important;
            color: var(--danger) !important;
        }
        
        .footer-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .footer-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-calendar-alt me-2"></i>Mis Reservaciones</h2>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?php echo $_GET['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $_GET['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Mesa</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['mesa']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['hora'])); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                            echo ($row['estado'] == 'confirmada') ? 'bg-success' : 
                                                 (($row['estado'] == 'pendiente') ? 'bg-warning' : 'bg-danger'); 
                                        ?>">
                                        <i class="fas 
                                            <?php 
                                                echo ($row['estado'] == 'confirmada') ? 'fa-check-circle' : 
                                                     (($row['estado'] == 'pendiente') ? 'fa-clock' : 'fa-times-circle'); 
                                            ?> me-1"></i>
                                        <?php echo ucfirst($row['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['estado'] == 'pendiente'): ?>
                                        <a href="confirmar_reservacion.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i>Confirmar
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-check me-1"></i>Confirmado
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>No tienes reservaciones registradas.
            </div>
        <?php endif; ?>

        <div class="footer-buttons">
            <a href="reservacion.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Hacer Nueva Reservación
            </a>
            <a href="ver_reservaciones.php" class="btn btn-warning">
                <i class="fas fa-sync-alt me-2"></i>Actualizar Reservaciones
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>