<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_componente'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];

    try {
        $stmt = $pdo->prepare("INSERT INTO componentes (nombre, descripcion, cantidad, prestado) VALUES (:nombre, :descripcion, :cantidad, 0)");
        $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion, 'cantidad' => $cantidad]);

        // Redireccionar para evitar envíos repetidos de formularios al actualizar
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } catch (PDOException $e) {
        die("Error al agregar el componente: " . $e->getMessage());
    }
}

// Consulta para obtener todos los componentes
$stmt = $pdo->query("SELECT id, nombre, descripcion, cantidad, prestado FROM componentes");
$componentes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Componentes</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Componentes</h1>
        
        <!-- Formulario para agregar un componente -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" value="1" required><br><br>
            
            <input type="submit" name="agregar_componente" value="Agregar Componente">
        </form>
        
        <hr>
        
        <!-- Mostrar todos los componentes -->
        <h2>Componentes Registrados</h2>
        <ul>
            <?php foreach ($componentes as $componente): ?>
                <li><?php echo htmlspecialchars($componente['nombre']); ?> - <?php echo htmlspecialchars($componente['descripcion']); ?> (Disponibles: <?php echo htmlspecialchars($componente['cantidad']); ?>)</li>
            <?php endforeach; ?>
        </ul>

        <a href="index.php" class="button">Volver al inicio</a>
    </div>
</body>
</html>
