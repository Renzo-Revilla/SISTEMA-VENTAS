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
  <title>Registrar Cliente</title>
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
  <h2 class="text-center mb-4">Registrar Empresa Cliente</h2>
  <form action="php/guardar_cliente.php" method="POST" id="clienteForm" novalidate>
    <div class="mb-3">
      <label class="form-label">Razón Social:</label>
      <input type="text" name="razon_social" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras y espacios">
    </div>

    <div class="mb-3">
      <label class="form-label">RUC:</label>
      <input type="text" name="ruc" class="form-control" required pattern="\d{11}" maxlength="11" title="Debe tener 11 dígitos numéricos">
    </div>

    <div class="mb-3">
      <label class="form-label">Dirección:</label>
      <input type="text" name="direccion" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Teléfono:</label>
      <input type="text" name="telefono" class="form-control" required pattern="\d{7,9}" maxlength="9" title="Debe tener entre 7 y 9 dígitos numéricos">
    </div>

    <div class="mb-3">
      <label class="form-label">Correo electrónico:</label>
      <input type="email" name="correo" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Registrar Cliente</button>
    <div class="text-center mt-3">
      <a href="menu.php" class="btn btn-secondary">← Volver al Menú</a>
    </div>
  </form>
</div>

<script>
document.getElementById("clienteForm").addEventListener("submit", function(e) {
  if (!this.checkValidity()) {
    alert("⚠️ Por favor completa correctamente todos los campos.");
    e.preventDefault();
  }
});
</script>

</body>
</html>
