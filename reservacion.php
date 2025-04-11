<?php
session_start();
include 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se recibió la fecha, hora y personas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'], $_POST['fecha'], $_POST['hora'], $_POST['personas'])) {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $personas = $_POST['personas'];

    // Obtener el user_id de la sesión
    $user_id = $_SESSION['user_id'];

    // Consulta SQL para obtener las mesas disponibles
    $sql = "SELECT * FROM mesas WHERE id NOT IN 
            (SELECT mesa_id FROM reservaciones WHERE fecha = ? AND hora = ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $fecha, $hora);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservación Elegante</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #6d4c41; /* Café elegante */
            --secondary-color: #d7ccc8; /* Café claro */
            --accent-color: #a1887f; /* Café medio */
            --text-dark: #3e2723; /* Café oscuro */
            --text-light: #f5f5f5; /* Blanco */
            --success-color: #81c784; /* Verde suave */
            --error-color: #e57373; /* Rojo suave */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            color: var(--text-dark);
            line-height: 1.6;
            background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }

        .reservation-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1000px;
            overflow: hidden;
            margin: 2rem auto;
            position: relative;
        }

        .reservation-header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .reservation-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(109, 76, 65, 0.9), rgba(167, 121, 103, 0.9));
            z-index: 0;
        }

        .reservation-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            letter-spacing: 1px;
        }

        .reservation-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .reservation-content {
            padding: 2rem;
        }

        .form-container {
            margin-bottom: 2rem;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            position: relative;
            display: inline-block;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--secondary-color);
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(161, 136, 127, 0.2);
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-submit:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .tables-container {
            margin-top: 2rem;
        }

        .tables-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            position: relative;
            display: inline-block;
        }

        .tables-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .tables-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .tables-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .tables-table td {
            padding: 15px;
            border-bottom: 1px solid var(--secondary-color);
        }

        .tables-table tr:last-child td {
            border-bottom: none;
        }

        .tables-table tr:hover {
            background-color: rgba(215, 204, 200, 0.2);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-available {
            background-color: rgba(129, 199, 132, 0.2);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .status-occupied {
            background-color: rgba(229, 115, 115, 0.2);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-book {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-book:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-disabled {
            background-color: var(--secondary-color);
            color: var(--text-dark);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .no-data {
            text-align: center;
            padding: 2rem;
            color: var(--accent-color);
            font-style: italic;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .reservation-header h1 {
                font-size: 2rem;
            }
            
            .reservation-content {
                padding: 1.5rem;
            }
            
            .tables-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .reservation-header {
                padding: 1.5rem;
            }
            
            .reservation-header h1 {
                font-size: 1.8rem;
            }
            
            .form-title, .tables-title {
                font-size: 1.5rem;
            }
            
            .tables-table th, 
            .tables-table td {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="reservation-container">
        <div class="reservation-header">
            <h1>Reserva tu Mesa</h1>
            <p>Disfruta de una experiencia culinaria excepcional</p>
        </div>
        
        <div class="reservation-content">
            <div class="form-container">
                <h2 class="form-title">Detalles de la Reservación</h2>
                <form action="reservacion.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hora">Hora</label>
                        <input type="time" class="form-control" id="hora" name="hora" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="personas">Número de personas</label>
                        <input type="number" class="form-control" id="personas" name="personas" min="1" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-search"></i> Ver Disponibilidad
                    </button>
                </form>
            </div>

            <?php if (isset($result)): ?>
                <div class="tables-container">
                    <h2 class="tables-title">Mesas Disponibles</h2>
                    
                    <table class="tables-table">
                        <thead>
                            <tr>
                                <th>Mesa</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>Mesa #<?php echo $row['id']; ?></td>
                                    <td><?php echo $row['descripcion']; ?></td>
                                    <td>
                                        <span class="status-badge 
                                            <?php echo ($row['estado'] == 'ocupada') ? 'status-occupied' : 'status-available'; ?>">
                                            <?php echo ucfirst($row['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['estado'] == 'disponible'): ?>
                                            <a href="reservar_mesa.php?mesa_id=<?php echo $row['id']; ?>&fecha=<?php echo $fecha; ?>&hora=<?php echo $hora; ?>" class="btn-action btn-book">
                                                <i class="fas fa-check"></i> Reservar
                                            </a>
                                        <?php else: ?>
                                            <button class="btn-action btn-disabled" disabled>
                                                <i class="fas fa-times"></i> Ocupada
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Establecer la fecha mínima como hoy
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fecha').min = today;
            
            // Efecto hover para las filas de la tabla
            const rows = document.querySelectorAll('.tables-table tbody tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                    this.style.transition = 'transform 0.3s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>