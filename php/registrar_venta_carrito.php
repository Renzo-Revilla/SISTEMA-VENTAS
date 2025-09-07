<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
  echo "<script>alert('❌ El carrito está vacío.'); window.location.href='../venta.php';</script>";
  exit();
}

$id_cliente = $_POST['id_cliente'];
$metodo_pago = $_POST['metodo_pago'];
$tipo_comprobante = $_POST['tipo_comprobante'];
$total = 0;

// Validar stock
foreach ($_SESSION['carrito'] as $item) {
  $id_producto = $item['id_producto'];
  $cantidad = $item['cantidad'];
  $producto = $conexion->query("SELECT stock FROM productos WHERE id = $id_producto")->fetch_assoc();

  if ($producto['stock'] < $cantidad) {
    echo "<script>alert('❌ Stock insuficiente para uno de los productos.'); window.location.href='../venta.php';</script>";
    exit();
  }
}

// Calcular total
foreach ($_SESSION['carrito'] as $item) {
  $id_producto = $item['id_producto'];
  $cantidad = $item['cantidad'];
  $producto = $conexion->query("SELECT precio FROM productos WHERE id = $id_producto")->fetch_assoc();
  $total += $producto['precio'] * $cantidad;
}

// Insertar venta
$conexion->query("INSERT INTO ventas (id_cliente, fecha, total) VALUES ($id_cliente, NOW(), $total)");
$id_venta = $conexion->insert_id;

// Insertar detalle y actualizar stock
foreach ($_SESSION['carrito'] as $item) {
  $id_producto = $item['id_producto'];
  $cantidad = $item['cantidad'];
  $producto = $conexion->query("SELECT precio, stock FROM productos WHERE id = $id_producto")->fetch_assoc();
  $subtotal = $producto['precio'] * $cantidad;

  $conexion->query("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal)
                    VALUES ($id_venta, $id_producto, $cantidad, $subtotal)");

  $nuevo_stock = $producto['stock'] - $cantidad;
  $conexion->query("UPDATE productos SET stock = $nuevo_stock WHERE id = $id_producto");
}

// Insertar comprobante
$conexion->query("INSERT INTO comprobantes (id_venta, tipo, metodo_pago)
                  VALUES ($id_venta, '$tipo_comprobante', '$metodo_pago')");

// Asiento contable
$caja = $conexion->query("SELECT id_cuenta FROM plan_cuentas WHERE nombre_cuenta = 'Caja'")->fetch_assoc()['id_cuenta'];
$ventas = $conexion->query("SELECT id_cuenta FROM plan_cuentas WHERE nombre_cuenta = 'Ventas'")->fetch_assoc()['id_cuenta'];
$conexion->query("INSERT INTO libro_diario (descripcion, id_cuenta_debe, id_cuenta_haber, monto)
                  VALUES ('Venta registrada', $caja, $ventas, $total)");

// Vaciar carrito
unset($_SESSION['carrito']);

// Redirigir
header("Location: ../comprobante.php?id_venta=$id_venta");
exit();
?>
