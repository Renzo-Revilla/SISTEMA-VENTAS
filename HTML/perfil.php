<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}

include("php/conexion.php");

$usuario = $_SESSION['usuario'];
$sql = $conexion->query("SELECT * FROM usuarios WHERE usuario = '$usuario'");

if ($sql && $sql->num_rows > 0) {
  $datos = $sql->fetch_assoc();
} else {
  echo "<script>alert('Usuario no encontrado.'); window.location.href='menu.php';</script>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil de Usuario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <h3 class="mb-4 text-center">👤 Mi Perfil</h3>
  <form action="php/actualizar_perfil.php" method="POST">
    <div class="mb-3">
      <label>Nombre:</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($datos['nombre']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Apellido:</label>
      <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($datos['apellido']) ?>" required>
    </div>
    <div class="mb-3">
      <label>DNI:</label>
      <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($datos['dni']) ?>" required maxlength="8" pattern="\d{8}">
    </div>
    <div class="mb-3">
      <label>Usuario (no editable):</label>
      <input type="text" class="form-control" value="<?= $datos['usuario'] ?>" readonly>
    </div>
    <div class="mb-3">
      <label>Nueva contraseña (opcional):</label>
      <input type="password" name="nueva_clave" class="form-control">
    </div>
    <div class="mb-3">
      <label>Rol:</label>
      <input type="text" class="form-control" value="<?= $datos['rol'] ?>" readonly>
    </div>
    <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
    <div class="text-center mt-3">
      <a href="menu.php" class="btn btn-secondary">← Volver al Menú</a>
    </div>
  </form>
</div>

</body>
</html>
