<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}
include("php/conexion.php");

$id_venta = $_GET['id_venta'] ?? 0;

// Datos generales (cliente + venta + comprobante)
$venta_sql = "
SELECT v.fecha, v.total, c.razon_social, c.ruc, c.direccion,
       comp.tipo, comp.metodo_pago
FROM ventas v
JOIN clientes c ON v.id_cliente = c.id
JOIN comprobantes comp ON comp.id_venta = v.id
WHERE v.id = $id_venta
";
$venta = $conexion->query($venta_sql)->fetch_assoc();

// Detalle de productos
$detalle_sql = "
SELECT p.nombre, d.cantidad, d.subtotal
FROM detalle_venta d
JOIN productos p ON d.id_producto = p.id
WHERE d.id_venta = $id_venta
";
$detalles = $conexion->query($detalle_sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .factura-box {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border: 1px solid #ddd;
      font-size: 16px;
    }
    .linea {
      border-top: 2px dashed #bbb;
      margin: 20px 0;
    }
    .titulo {
      text-align: center;
      font-weight: bold;
      font-size: 24px;
      margin-bottom: 20px;
    }
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body class="bg-light">
  <div class="factura-box mt-5 shadow">

    <div class="titulo">FACTURA</div>

    <p><strong>Empresa:</strong> <?= $venta['razon_social'] ?></p>
    <p><strong>RUC:</strong> <?= $venta['ruc'] ?></p>
    <p><strong>Dirección:</strong> <?= $venta['direccion'] ?></p>
    <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>

    <div class="linea"></div>

    <h5>Detalle de productos:</h5>
    <table class="table">
      <thead>
        <tr>
          <th>Producto</th>
          <th class="text-end">Cantidad</th>
          <th class="text-end">Subtotal (S/)</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($fila = $detalles->fetch_assoc()): ?>
          <tr>
            <td><?= $fila['nombre'] ?></td>
            <td class="text-end"><?= $fila['cantidad'] ?></td>
            <td class="text-end"><?= number_format($fila['subtotal'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="linea"></div>

    <p><strong>Total:</strong> S/ <?= number_format($venta['total'], 2) ?></p>
    <p><strong>Método de pago:</strong> <?= ucfirst($venta['metodo_pago']) ?></p>
    <p><strong>Tipo de Comprobante:</strong> <?= ucfirst($venta['tipo']) ?></p>

    <div class="linea"></div>

    <p class="text-center">Gracias por su compra</p>

    <div class="text-center mt-4 no-print">
      <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
      <a href="menu.php" class="btn btn-secondary">Volver al Menú</a>
    </div>
  </div>
</body>
</html>
