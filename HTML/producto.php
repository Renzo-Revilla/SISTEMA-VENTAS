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
  <title>Registrar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
    }
  </style>
</head>
<body>

<div class="container bg-white p-4 shadow rounded">
  <h2 class="text-center mb-4">Registrar Producto (Laptop)</h2>
  <form action="php/guardar_producto.php" method="POST" id="productoForm" novalidate>
    <div class="mb-3">
      <label class="form-label">Nombre del producto:</label>
      <input type="text" name="nombre" class="form-control" required pattern="[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras, números y espacios">
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción:</label>
      <textarea name="descripcion" class="form-control" rows="3" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Precio (S/):</label>
      <input type="number" name="precio" class="form-control" required step="0.01" min="0.01" title="Solo valores positivos mayores que cero">
    </div>

    <div class="mb-3">
      <label class="form-label">Stock:</label>
      <input type="number" name="stock" class="form-control" required min="0" title="Cantidad de unidades disponibles">
    </div>

    <button type="submit" class="btn btn-primary w-100">Registrar Producto</button>
    <div class="text-center mt-3">
      <a href="menu.php" class="btn btn-secondary">← Volver al Menú</a>
    </div>
  </form>
</div>

<script>
document.getElementById("productoForm").addEventListener("submit", function(e) {
  if (!this.checkValidity()) {
    alert("⚠️ Por favor completa correctamente todos los campos.");
    e.preventDefault();
  }
});
</script>

</body>
</html>
