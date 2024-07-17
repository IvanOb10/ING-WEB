<?php
$host = 'sql302.infinityfree.com';
$db = 'if0_36879308_ingeweb';
$user = 'if0_36879308';
$pass = '9F09oht09xzzM';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}
?>
