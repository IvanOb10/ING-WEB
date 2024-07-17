<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_equipo'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    try {
        $stmt = $pdo->prepare("INSERT INTO equipos (nombre, descripcion, prestado) VALUES (:nombre, :descripcion, 0)");
        $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion]);

        // Redireccionar para evitar envíos repetidos de formularios al actualizar
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } catch (PDOException $e) {
        die("Error al agregar el equipo: " . $e->getMessage());
    }
}

// Consulta para obtener todos los equipos
$stmt = $pdo->query("SELECT id, nombre, descripcion FROM equipos");
$equipos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Equipos</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Equipos</h1>
        
        <!-- Formulario para agregar un equipo -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>
            
            <input type="submit" name="agregar_equipo" value="Agregar Equipo">
        </form>
        
        <hr>
        
        <!-- Mostrar todos los equipos -->
        <h2>Equipos Registrados</h2>
        <ul>
            <?php foreach ($equipos as $equipo): ?>
                <li><?php echo htmlspecialchars($equipo['nombre']); ?> - <?php echo htmlspecialchars($equipo['descripcion']); ?></li>
            <?php endforeach; ?>
        </ul>

        <a href="index.php" class="button">Volver al inicio</a>
    </div>
</body>
</html>
