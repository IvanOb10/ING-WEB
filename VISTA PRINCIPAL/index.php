<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>IEA</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="menu.css">
</head>
<body>
    <div class="open-menu" onclick="toggleMenu()">&#9776;</div>

    <div class="menu" id="menu">
        <span class="close-btn" onclick="toggleMenu()">&times;</span>
        <div class="menu-content">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Hola, <?php echo htmlspecialchars($_SESSION['username']); ?>.</p>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="equipos.php" onclick="toggleMenu()">Gestionar Equipos</a>
                    <a href="componentes.php" onclick="toggleMenu()">Gestionar Componentes</a>
                    <a href="logout.php" onclick="toggleMenu()">Cerrar sesión</a>
                <?php else: ?>
                    <a href="ver_equipos.php" onclick="toggleMenu()">Ver Equipos</a>
                    <a href="ver_componentes.php" onclick="toggleMenu()">Ver Componentes</a>
                    <a href="mis_solicitudes.php" onclick="toggleMenu()">Mis Solicitudes</a>
                    <a href="logout.php" onclick="toggleMenu()">Cerrar sesión</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" onclick="toggleMenu()">Iniciar sesión</a>
                <a href="register.php" onclick="toggleMenu()">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container fade-in" id="main-content">
        <h1>Bienvenido al Sistema de Gestión de Inventario</h1>
        <div class="center">
            <picture>
                <img src="1.jpg" alt="Imagen de Bienvenida">
            </picture>
        </div>
    </div>

    <div class="container text-container" id="loan-intro">
    <p>
        <br>
        <br>
        </p>
        <h1>Introducción</h1>
        <div class="center">
            <p>Bienvenido al sistema de préstamo de material del Instituto de Investigación Avanzada en Electrónica (IEA). Este sistema te permite gestionar eficientemente el préstamo y la devolución de equipos y componentes, asegurando un uso óptimo y organizado de nuestros recursos.</p>
            <p>Nuestro objetivo es facilitar a investigadores y personal autorizado el acceso a los recursos necesarios para sus proyectos, manteniendo un control riguroso de inventario y un seguimiento detallado de cada transacción.</p>
            <p>Utiliza las opciones disponibles en el menú para solicitar equipos, gestionar reservas pendientes y consultar el estado de disponibilidad de los componentes. Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar con nuestro equipo administrativo.</p>
        </div>
    </div>

    <div class="container text-container" id="loan-intro-2">
    <p>
        <br>
        <br>
        </p>
        <h1>Préstamo de Material: Normativas y Procedimientos</h1>
        <div class="center">
        <p>Reglas y Procedimientos:
            <ul>
                <li>Los usuarios deben estar registrados y contar con aprobación para solicitar equipos y componentes.</li>
                <li>Cada solicitud de préstamo está sujeta a disponibilidad y a las políticas de uso establecidas por el IEA.</li>
                <li>Los equipos deben ser devueltos en las condiciones acordadas y dentro del plazo establecido.</li>
                <li>Los usuarios son responsables del cuidado y uso adecuado de los materiales prestados.</li>
                <li>Se puede consultar la disponibilidad de equipos y realizar reservas a través del sistema en línea.</li>
            </ul>
        </p>
        <p>Utiliza las opciones disponibles en el menú para solicitar equipos, gestionar reservas pendientes y consultar el estado de disponibilidad de los componentes. Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar con nuestro equipo administrativo.</p>
   
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
     function toggleMenu() {
            var menu = document.getElementById("menu");
            document.body.classList.toggle("menu-open");
        }
        // Función para detectar cuando el contenedor está visible en la ventana
        function checkVisible(element) {
            let rect = element.getBoundingClientRect();
            return (rect.top <= window.innerHeight * 0.75 && rect.bottom >= 0);
        }

        // Función para manejar el evento de scroll
        function handleScroll() {
            let textContainers = document.querySelectorAll('.text-container');
            textContainers.forEach(function(textContainer) {
                if (checkVisible(textContainer)) {
                    textContainer.classList.add('appear');
                } else {
                    textContainer.classList.remove('appear');
                }
            });
        }

        // Agregar evento de scroll para activar la animación
        window.addEventListener('scroll', handleScroll);
    </script>
</body>
</html>

