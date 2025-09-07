<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("conexion.php");

$razon_social = $_POST['razon_social'];
$ruc = $_POST['ruc'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$sql = "INSERT INTO clientes (razon_social, ruc, direccion, telefono, email)
        VALUES ('$razon_social', '$ruc', '$direccion', '$telefono', '$email')";

if ($conexion->query($sql)) {
    // Si se guarda correctamente, vuelve al formulario
    header("Location: ../cliente.php");
    exit();
} else {
    // Si hay error, lo muestra en pantalla
    echo "❌ Error al registrar cliente: " . $conexion->error;
}
?>
