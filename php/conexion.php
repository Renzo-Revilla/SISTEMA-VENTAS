<?php
$conexion = new mysqli("localhost", "root", "", "ventas_laptops");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
