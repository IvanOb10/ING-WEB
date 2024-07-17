<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'researcher') {
    header('Location: login.php');
    exit();
}

// Consulta para obtener las solicitudes del usuario actual
$stmt = $pdo->prepare("SELECT s.id, e.nombre AS equipo_nombre, e.descripcion AS equipo_descripcion, s.fecha_solicitud, s.estado
                       FROM solicitudes s
                       INNER JOIN equipos e ON s.equipo_id = e.id
                       WHERE s.usuario_id = :usuario_id
                       ORDER BY s.fecha_solicitud DESC");
$stmt->execute(['usuario_id' => $_SESSION['user_id']]);
$solicitudes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis solicitudes de equipo</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Mis Solicitudes de equipo</h1>
        
        <!-- Tabla para mostrar las solicitudes del investigador -->
        <table>
            <thead>
                <tr>
                    <th>ID Solicitud</th>
                    <th>Equipo Solicitado</th>
                    <th>Descripción del Equipo</th>
                    <th>Fecha de Solicitud</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $solicitud): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitud['id']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['equipo_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['equipo_descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="button">Volver al inicio</a>
    </div>
</body>
</html>
