<?php
include("conexion.php");

$id_cliente = $_POST['id_cliente'];
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];
$metodo_pago = $_POST['metodo_pago'];
$tipo_comprobante = $_POST['tipo_comprobante'];

// Obtener precio y stock del producto
$producto = $conexion->query("SELECT precio, stock FROM productos WHERE id = $id_producto")->fetch_assoc();
$precio = $producto['precio'];
$stock_actual = $producto['stock'];
$subtotal = $precio * $cantidad;

// Verificar stock
if ($cantidad > $stock_actual) {
    echo "<script>alert('❌ Stock insuficiente. Solo hay $stock_actual unidades.'); window.location.href='../venta.php';</script>";
    exit();
}

// Insertar venta
$conexion->query("INSERT INTO ventas (id_cliente, fecha, total) VALUES ($id_cliente, NOW(), $subtotal)");
$id_venta = $conexion->insert_id;

// Insertar detalle de venta (sin precio_unitario)
$conexion->query("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal)
                  VALUES ($id_venta, $id_producto, $cantidad, $subtotal)");

// Insertar comprobante
$conexion->query("INSERT INTO comprobantes (id_venta, tipo, metodo_pago)
                  VALUES ($id_venta, '$tipo_comprobante', '$metodo_pago')");

// Actualizar stock
$nuevo_stock = $stock_actual - $cantidad;
$conexion->query("UPDATE productos SET stock = $nuevo_stock WHERE id = $id_producto");

// === ASIENTO CONTABLE ===
$caja = $conexion->query("SELECT id_cuenta FROM plan_cuentas WHERE nombre_cuenta = 'Caja'")->fetch_assoc()['id_cuenta'];
$ventas = $conexion->query("SELECT id_cuenta FROM plan_cuentas WHERE nombre_cuenta = 'Ventas'")->fetch_assoc()['id_cuenta'];

$conexion->query("INSERT INTO libro_diario (descripcion, id_cuenta_debe, id_cuenta_haber, monto)
                  VALUES ('Venta registrada', $caja, $ventas, $subtotal)");

// Redirigir a factura
header("Location: ../comprobante.php?id_venta=$id_venta");
exit();
?>
