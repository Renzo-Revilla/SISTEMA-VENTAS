<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}

include("php/conexion.php");

$registros = $conexion->query("
  SELECT ld.fecha, ld.descripcion, 
         pc1.nombre_cuenta AS cuenta_debe, 
         pc2.nombre_cuenta AS cuenta_haber, 
         ld.monto
  FROM libro_diario ld
  JOIN plan_cuentas pc1 ON ld.id_cuenta_debe = pc1.id_cuenta
  JOIN plan_cuentas pc2 ON ld.id_cuenta_haber = pc2.id_cuenta
  ORDER BY ld.fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📘 Libro Diario - Contabilidad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3 class="mb-4">📘 Libro Diario</h3>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Cuenta Debe</th>
        <th>Cuenta Haber</th>
        <th>Monto</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $registros->fetch_assoc()): ?>
      <tr>
        <td><?= $row['fecha'] ?></td>
        <td><?= $row['descripcion'] ?></td>
        <td><?= $row['cuenta_debe'] ?></td>
        <td><?= $row['cuenta_haber'] ?></td>
        <td>S/ <?= number_format($row['monto'], 2) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="menu.php" class="btn btn-secondary">← Volver al Menú</a>
</div>
</body>
</html>
