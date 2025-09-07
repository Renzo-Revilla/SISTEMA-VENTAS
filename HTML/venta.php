<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.html");
  exit();
}
include("php/conexion.php");

if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

if (isset($_POST['agregar_al_carrito'])) {
  $id_producto = $_POST['id_producto'];
  $cantidad = $_POST['cantidad'];

  if (!isset($_SESSION['venta_info'])) {
    $_SESSION['venta_info'] = [
      'id_cliente' => $_POST['id_cliente'],
      'metodo_pago' => $_POST['metodo_pago'],
      'tipo_comprobante' => $_POST['tipo_comprobante']
    ];
  }

  $yaExiste = false;
  foreach ($_SESSION['carrito'] as &$item) {
    if ($item['id_producto'] == $id_producto) {
      $item['cantidad'] += $cantidad;
      $yaExiste = true;
      break;
    }
  }
  if (!$yaExiste) {
    $_SESSION['carrito'][] = ['id_producto' => $id_producto, 'cantidad' => $cantidad];
  }
}

if (isset($_GET['eliminar'])) {
  $eliminar_id = $_GET['eliminar'];
  foreach ($_SESSION['carrito'] as $index => $item) {
    if ($item['id_producto'] == $eliminar_id) {
      unset($_SESSION['carrito'][$index]);
      $_SESSION['carrito'] = array_values($_SESSION['carrito']);
      break;
    }
  }
  if (empty($_SESSION['carrito'])) {
    unset($_SESSION['venta_info']);
  }
  header("Location: venta.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Venta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      max-width: 700px;
      margin-top: 40px;
      position: relative;
    }
    .select2-container .select2-selection--single {
      height: 38px;
    }
    .carrito-icon {
      position: absolute;
      top: -10px;
      right: -10px;
      width: 40px;
      cursor: pointer;
    }
    .carrito-tabla {
      position: fixed;
      top: 80px;
      right: 20px;
      width: 400px;
      background: white;
      border: 1px solid #ccc;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      padding: 15px;
      display: none;
      z-index: 1000;
    }
  </style>
</head>
<body>

<div class="container bg-white p-4 shadow rounded">
  <img src="img/carrito.png" class="carrito-icon" id="abrirCarrito" title="Ver carrito de compras">

  <h2 class="text-center mb-4">Registrar Venta</h2>
  <form action="" method="POST" id="ventaForm" novalidate>
    <div class="mb-3">
      <label class="form-label">Buscar Empresa Cliente:</label>
      <select name="id_cliente" class="form-select" id="clienteSelect" required <?= !empty($_SESSION['carrito']) ? 'disabled' : '' ?>>
        <option disabled value="">-- Buscar y seleccionar una empresa --</option>
        <?php
        $clientes = $conexion->query("SELECT id, razon_social, ruc FROM clientes");
        while ($c = $clientes->fetch_assoc()) {
          $selected = (!empty($_SESSION['venta_info']['id_cliente']) && $_SESSION['venta_info']['id_cliente'] == $c['id']) ? 'selected' : '';
          echo "<option value='{$c['id']}' $selected>{$c['razon_social']} ({$c['ruc']})</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Buscar Producto (Laptop):</label>
      <select name="id_producto" class="form-select" id="productoSelect" required>
        <option disabled selected value="">-- Buscar y seleccionar producto --</option>
        <?php
        $productos = $conexion->query("SELECT id, nombre, precio FROM productos WHERE stock > 0");
        while ($p = $productos->fetch_assoc()) {
          echo "<option value='{$p['id']}'>{$p['nombre']} - S/ {$p['precio']}</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Cantidad:</label>
      <input type="number" name="cantidad" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Método de Pago:</label>
      <select name="metodo_pago" class="form-select" required <?= !empty($_SESSION['carrito']) ? 'disabled' : '' ?>>
        <option value="efectivo" <?= (!empty($_SESSION['venta_info']['metodo_pago']) && $_SESSION['venta_info']['metodo_pago'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
        <option value="yape" <?= (!empty($_SESSION['venta_info']['metodo_pago']) && $_SESSION['venta_info']['metodo_pago'] == 'yape') ? 'selected' : '' ?>>Yape</option>
        <option value="transferencia" <?= (!empty($_SESSION['venta_info']['metodo_pago']) && $_SESSION['venta_info']['metodo_pago'] == 'transferencia') ? 'selected' : '' ?>>Transferencia</option>
        <option value="tarjeta" <?= (!empty($_SESSION['venta_info']['metodo_pago']) && $_SESSION['venta_info']['metodo_pago'] == 'tarjeta') ? 'selected' : '' ?>>Tarjeta</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Tipo de Comprobante:</label>
      <select name="tipo_comprobante" class="form-select" required <?= !empty($_SESSION['carrito']) ? 'disabled' : '' ?>>
        <option value="factura" selected>Factura</option>
      </select>
    </div>

    <button type="submit" name="agregar_al_carrito" class="btn btn-warning w-100">🛒 Agregar al carrito de compras</button>

    <div class="text-center mt-3">
      <a href="menu.php" class="btn btn-secondary">← Volver al Menú</a>
    </div>
  </form>
</div>

<div class="carrito-tabla" id="carritoTabla">
  <h6>🧾 Productos agregados:</h6>
  <table class="table table-sm">
    <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Acción</th></tr></thead>
    <tbody>
    <?php 
    $total = 0;
    foreach ($_SESSION['carrito'] as $item):
      $prod = $conexion->query("SELECT nombre, precio FROM productos WHERE id = {$item['id_producto']}")->fetch_assoc();
      $subtotal = $prod['precio'] * $item['cantidad'];
      $total += $subtotal;
    ?>
      <tr>
        <td><?= $prod['nombre'] ?></td>
        <td><?= $item['cantidad'] ?></td>
        <td>S/ <?= number_format($subtotal, 2) ?></td>
        <td><a href="?eliminar=<?= $item['id_producto'] ?>" class="btn btn-sm btn-danger">🗑</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p class="text-end fw-bold">Total: S/ <?= number_format($total, 2) ?></p>
  <form action="php/registrar_venta_carrito.php" method="POST">
    <input type="hidden" name="id_cliente" value="<?= $_SESSION['venta_info']['id_cliente'] ?? '' ?>">
    <input type="hidden" name="metodo_pago" value="<?= $_SESSION['venta_info']['metodo_pago'] ?? '' ?>">
    <input type="hidden" name="tipo_comprobante" value="<?= $_SESSION['venta_info']['tipo_comprobante'] ?? '' ?>">
    <button type="submit" class="btn btn-success w-100">✅ Registrar Venta</button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('#clienteSelect').select2({ placeholder: "Buscar empresa..." });
    $('#productoSelect').select2({ placeholder: "Buscar producto..." });
    $('#abrirCarrito').click(function() {
      $('#carritoTabla').toggle();
    });
  });
</script>

</body>
</html>
