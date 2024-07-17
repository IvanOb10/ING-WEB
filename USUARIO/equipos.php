<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Verificar si se ha enviado una solicitud para liberar un equipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['liberar_equipo'])) {
    $equipo_id = $_POST['equipo_id'];

    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Liberar el equipo en la tabla equipos
        $stmt_equipo = $pdo->prepare("UPDATE equipos SET prestado = 0, prestado_a = NULL WHERE id = :equipo_id");
        $stmt_equipo->execute(['equipo_id' => $equipo_id]);

        // Eliminar la solicitud correspondiente en la tabla solicitudes
        $stmt_solicitud = $pdo->prepare("DELETE FROM solicitudes WHERE equipo_id = :equipo_id");
        $stmt_solicitud->execute(['equipo_id' => $equipo_id]);

        // Commit de la transacción
        $pdo->commit();

        // Redireccionar para evitar envíos repetidos de formularios al actualizar
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } catch (PDOException $e) {
        // Manejo de errores
        $pdo->rollBack();
        die("Error al liberar el equipo: " . $e->getMessage());
    }
}

// Consulta para obtener todos los equipos, tanto prestados como disponibles
$stmt = $pdo->prepare("SELECT e.id, e.nombre, e.descripcion, e.prestado, u.username AS prestado_a
                       FROM equipos e
                       LEFT JOIN users u ON e.prestado_a = u.id");
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
        <a href="agregarequipo.php" id="agregar-equipo" class="button">Agregar Equipo</a>
        <p><br>
        </p>
        <!-- Tabla para mostrar todos los equipos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Prestado</th>
                    <th>Prestado a</th>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <th>Acción</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $equipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo['id']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['descripcion']); ?></td>
                        <td><?php echo $equipo['prestado'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($equipo['prestado_a']); ?></td>
                        <?php if ($_SESSION['role'] == 'admin' && $equipo['prestado']): ?>
                            <td>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="equipo_id" value="<?php echo $equipo['id']; ?>">
                                    <input type="submit" name="liberar_equipo" value="Liberar">
                                </form>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="button">Volver al inicio</a>
    </div>
</body>
</html>

</html>
