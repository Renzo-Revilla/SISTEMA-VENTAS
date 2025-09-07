<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario'])) {
  header("Location: ../index.html");
  exit();
}

$usuario = $_SESSION['usuario'];
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$dni = trim($_POST['dni']);
$nueva_clave = trim($_POST['nueva_clave']);

// Validar campos
if (empty($nombre) || empty($apellido) || empty($dni)) {
  echo "<script>alert('❌ Todos los campos obligatorios deben estar completos.'); window.location.href='../perfil.php';</script>";
  exit();
}

if (!preg_match('/^\d{8}$/', $dni)) {
  echo "<script>alert('❌ DNI inválido. Debe tener 8 dígitos.'); window.location.href='../perfil.php';</script>";
  exit();
}

// Armar consulta de actualización
if (!empty($nueva_clave)) {
  $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', dni='$dni', clave='$nueva_clave' WHERE usuario='$usuario'";
} else {
  $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', dni='$dni' WHERE usuario='$usuario'";
}

if ($conexion->query($sql)) {
  echo "<script>alert('✅ Perfil actualizado correctamente.'); window.location.href='../perfil.php';</script>";
} else {
  echo "<script>alert('❌ Error: " . $conexion->error . "'); window.location.href='../perfil.php';</script>";
}
?>
