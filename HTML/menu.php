<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú Principal - Sistema de Ventas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      display: flex;
      font-family: Arial;
    }

    .sidebar {
      width: 220px;
      background-color: #343a40;
      color: white;
      height: 100vh;
      padding-top: 30px;
      position: fixed;
    }

    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      transition: background 0.2s;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .main-content {
      margin-left: 220px;
      padding: 30px;
      width: 100%;
      background-color: #f8f9fa;
      min-height: 100vh;
    }

    .card-option {
      text-align: center;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }

    .card-option:hover {
      transform: scale(1.02);
    }

    .card-option img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<!-- Barra lateral izquierda -->
<div class="sidebar">
  <a href="menu.php">🏠 Inicio</a>
  <a href="cliente.php">👥 Registrar Cliente</a>
  <a href="producto.php">📦 Registrar Producto</a>
  <a href="venta.php">🛒 Realizar Venta</a>
  <a href="historial.php">📄 Historial de Comprobante</a>
  <a href="perfil.php">👤 Perfil</a> <!-- 👈 NUEVO BOTÓN -->
  <a href="contabilidad.php">📘 Contabilidad</a>
  <a href="php/logout.php">🚪 Cerrar Sesión</a>
</div>


<!-- Contenido principal -->
<div class="main-content">
  <h2>Bienvenido al Sistema de Ventas</h2>
  <p>Seleccione una opción:</p>

  <div class="row row-cols-1 row-cols-md-2 g-4 mt-4">
    <div class="col">
      <div class="card-option">
        <img src="img/cliente.png" alt="Registrar Cliente">
        <div class="card-title">Registrar Cliente</div>
        <a href="cliente.php" class="btn btn-primary">Acceder</a>
      </div>
    </div>

    <div class="col">
      <div class="card-option">
        <img src="img/producto.png" alt="Registrar Producto">
        <div class="card-title">Registrar Producto</div>
        <a href="producto.php" class="btn btn-primary">Acceder</a>
      </div>
    </div>

    <div class="col">
      <div class="card-option">
        <img src="img/venta.png" alt="Realizar Venta">
        <div class="card-title">Realizar Venta</div>
        <a href="venta.php" class="btn btn-primary">Acceder</a>
      </div>
    </div>

    <div class="col">
      <div class="card-option">
        <img src="img/comprobante.png" alt="Historial de Comprobante">
        <div class="card-title">Historial de Comprobante</div>
        <a href="historial.php" class="btn btn-primary">Acceder</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
