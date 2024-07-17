<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Verificar si se ha enviado una solicitud para liberar un componente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['liberar_componente'])) {
    $componente_id = $_POST['componente_id'];

    // Código para actualizar la base de datos y marcar el componente como disponible
    $stmt = $pdo->prepare("UPDATE componentes SET prestado = 0, prestado_a = NULL WHERE id = :componente_id");
    $stmt->execute(['componente_id' => $componente_id]);

    // Redireccionar a esta página para evitar envíos repetidos de formularios al actualizar
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

// Consulta para obtener todos los componentes, tanto prestados como disponibles
$stmt = $pdo->prepare("SELECT c.id, c.nombre, c.descripcion, c.cantidad, c.prestado, u.username AS prestado_a
                       FROM componentes c
                       LEFT JOIN users u ON c.prestado_a = u.id");
$stmt->execute();
$componentes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Componentes</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ver Componentes</h1>
        <a href="agregarcomponente.php" id="agregar-componente" class="button">Agregar componente</a>
        <p><br>
        </p>
        <!-- Tabla para mostrar todos los componentes -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Prestado</th>
                    <th>Prestado a</th>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <th>Acción</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($componentes as $componente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($componente['id']); ?></td>
                        <td><?php echo htmlspecialchars($componente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($componente['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($componente['cantidad']); ?></td>
                        <td><?php echo $componente['prestado'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($componente['prestado_a']); ?></td>
                        <?php if ($_SESSION['role'] == 'admin' && $componente['prestado']): ?>
                            <td>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="componente_id" value="<?php echo $componente['id']; ?>">
                                    <input type="submit" name="liberar_componente" value="Liberar">
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
