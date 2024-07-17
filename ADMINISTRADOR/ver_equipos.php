<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'researcher') {
    header('Location: login.php');
    exit();
}

// Verificar si se ha enviado una solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['solicitar_equipo'])) {
    $equipo_id = $_POST['equipo_id'];
    $usuario_id = $_SESSION['user_id'];

    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Actualizar equipo como prestado
        $stmt_update = $pdo->prepare("UPDATE equipos SET prestado = 1, prestado_a = :usuario_id WHERE id = :equipo_id");
        $stmt_update->execute(['usuario_id' => $usuario_id, 'equipo_id' => $equipo_id]);

        // Insertar solicitud en la tabla de solicitudes
        $stmt_insert = $pdo->prepare("INSERT INTO solicitudes (usuario_id, equipo_id, fecha_solicitud) VALUES (:usuario_id, :equipo_id, NOW())");
        $stmt_insert->execute(['usuario_id' => $usuario_id, 'equipo_id' => $equipo_id]);

        // Commit de la transacción
        $pdo->commit();

        // Redireccionar para evitar envíos repetidos de formularios al actualizar
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } catch (PDOException $e) {
        // Manejo de errores
        $pdo->rollBack();
        die("Error al procesar la solicitud: " . $e->getMessage());
    }
}

// Consulta para obtener equipos disponibles
$stmt = $pdo->prepare("SELECT e.id, e.nombre, e.descripcion, u.username AS prestado_a
                       FROM equipos e
                       LEFT JOIN users u ON e.prestado_a = u.id
                       WHERE e.prestado = 0");
$stmt->execute();
$equipos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Equipos</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ver Equipos</h1>
        
        <!-- Tabla para mostrar equipos disponibles -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Solicitar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $equipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo['id']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['descripcion']); ?></td>
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="equipo_id" value="<?php echo $equipo['id']; ?>">
                                <input type="submit" name="solicitar_equipo" value="Solicitar">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="button">Volver al inicio</a>
    </div>
</body>
</html>

