<?php
include("conexion.php");

$razon_social = $_POST['razon_social'];
$ruc = $_POST['ruc'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$sql = "INSERT INTO clientes (razon_social, ruc, direccion, telefono, email)
        VALUES ('$razon_social', '$ruc', '$direccion', '$telefono', '$email')";

if ($conexion->query($sql)) {
    echo "✅ Empresa registrada correctamente. <a href='../cliente.php'>Registrar otra</a> | <a href='../menu.php'>Volver al Menú</a>";
} else {
    echo "❌ Error al registrar: " . $conexion->error;
}
?>
