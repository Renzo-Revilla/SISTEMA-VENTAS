<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");

$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion']);
$precio = trim($_POST['precio']);
$stock = trim($_POST['stock']);

// Validaciones básicas
if (empty($nombre) || empty($descripcion) || empty($precio) || empty($stock)) {
  echo "<script>alert('❌ Todos los campos son obligatorios.'); window.location.href='../producto.php';</script>";
  exit();
}

if (!is_numeric($precio) || $precio <= 0) {
  echo "<script>alert('❌ El precio debe ser un número mayor a cero.'); window.location.href='../producto.php';</script>";
  exit();
}

if (!is_numeric($stock) || $stock < 0) {
  echo "<script>alert('❌ El stock debe ser un número igual o mayor a cero.'); window.location.href='../producto.php';</script>";
  exit();
}

// Insertar producto en base de datos
$sql = "INSERT INTO productos (nombre, descripcion, precio, stock)
        VALUES ('$nombre', '$descripcion', '$precio', '$stock')";

if ($conexion->query($sql)) {
  echo "<script>alert('✅ Producto registrado correctamente.'); window.location.href='../producto.php';</script>";
} else {
  echo "<script>alert('❌ Error al registrar el producto: " . $conexion->error . "'); window.location.href='../producto.php';</script>";
}
?>
