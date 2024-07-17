<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'researcher') {
    header('Location: login.php');
    exit();
}

// Verificar si se ha enviado una solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['solicitar_componente'])) {
    $componente_id = $_POST['componente_id'];
    $usuario_id = $_SESSION['user_id'];

    // Código para actualizar la base de datos y marcar el componente como prestado
    $stmt = $pdo->prepare("UPDATE componentes SET prestado = 1, prestado_a = :usuario_id WHERE id = :componente_id");
    $stmt->execute(['usuario_id' => $usuario_id, 'componente_id' => $componente_id]);

    // Redireccionar a esta página para evitar envíos repetidos de formularios al actualizar
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

// Consulta para obtener componentes disponibles
$stmt = $pdo->prepare("SELECT c.id, c.nombre, c.descripcion, c.cantidad, u.username AS prestado_a
                       FROM componentes c
                       LEFT JOIN users u ON c.prestado_a = u.id
                       WHERE c.prestado = 0");
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
        
        <!-- Tabla para mostrar componentes disponibles -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Solicitar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($componentes as $componente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($componente['id']); ?></td>
                        <td><?php echo htmlspecialchars($componente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($componente['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($componente['cantidad']); ?></td>
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="componente_id" value="<?php echo $componente['id']; ?>">
                                <input type="submit" name="solicitar_componente" value="Solicitar">
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
