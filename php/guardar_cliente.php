<?php
// Mostrar errores si los hay
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");

// Obtener datos del formulario
$razon_social = trim($_POST['razon_social']);
$ruc = trim($_POST['ruc']);
$direccion = trim($_POST['direccion']);
$telefono = trim($_POST['telefono']);
$email = trim($_POST['correo']); // el input se llama "correo", pero en la base es "email"

// Validar campos obligatorios
if (empty($razon_social) || empty($ruc) || empty($direccion) || empty($telefono) || empty($email)) {
  echo "<script>alert('❌ Todos los campos son obligatorios.'); window.location.href='../cliente.php';</script>";
  exit();
}

// Validar RUC (11 dígitos)
if (!preg_match("/^[0-9]{11}$/", $ruc)) {
  echo "<script>alert('❌ El RUC debe tener exactamente 11 dígitos.'); window.location.href='../cliente.php';</script>";
  exit();
}

// Validar correo
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "<script>alert('❌ El correo electrónico no es válido.'); window.location.href='../cliente.php';</script>";
  exit();
}

// Insertar en la base de datos
$sql = "INSERT INTO clientes (razon_social, ruc, direccion, telefono, email)
        VALUES ('$razon_social', '$ruc', '$direccion', '$telefono', '$email')";

if ($conexion->query($sql)) {
  echo "<script>alert('✅ Empresa registrada correctamente.'); window.location.href='../cliente.php';</script>";
} else {
  echo "<script>alert('❌ Error al registrar la empresa: " . $conexion->error . "'); window.location.href='../cliente.php';</script>";
}
?>
