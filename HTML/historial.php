<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}
include("php/conexion.php");

// Obtener lista de comprobantes con información relacionada
$sql = "
SELECT v.id AS id_venta, v.fecha, v.total, 
       c.razon_social, c.ruc,
       comp.tipo, comp.metodo_pago
FROM ventas v
JOIN clientes c ON v.id_cliente = c.id
JOIN comprobantes comp ON comp.id_venta = v.id
ORDER BY v.fecha DESC
";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Comprobantes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="text-center mb-4">Historial de Comprobantes</h2>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th># Venta</th>
          <th>Fecha</th>
          <th>Empresa</th>
          <th>RUC</th>
          <th>Total (S/)</th>
          <th>Comprobante</th>
          <th>Método de Pago</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
          <tr>
            <td><?= $fila['id_venta'] ?></td>
            <td><?= $fila['fecha'] ?></td>
            <td><?= $fila['razon_social'] ?></td>
            <td><?= $fila['ruc'] ?></td>
            <td><?= number_format($fila['total'], 2) ?></td>
            <td><?= ucfirst($fila['tipo']) ?></td>
            <td><?= ucfirst($fila['metodo_pago']) ?></td>
            <td>
              <a href="comprobante.php?id_venta=<?= $fila['id_venta'] ?>" class="btn btn-sm btn-primary" target="_blank">Ver</a>
              <a href="generar_factura.php?id_venta=<?= $fila['id_venta'] ?>" class="btn btn-sm btn-danger">Descargar PDF</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="text-center mt-4">
      <a href="menu.php" class="btn btn-secondary">Volver al Menú</a>
    </div>
  </div>
</body>
</html>
