<?php
// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la librería Dompdf
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Conexión a base de datos
include("php/conexion.php");

// Validar parámetro
$id_venta = $_GET['id_venta'] ?? 0;
if ($id_venta == 0) {
    die("❌ ID de venta inválido.");
}

// Obtener datos de la venta, cliente y comprobante
$venta_sql = "
SELECT v.fecha, v.total, c.razon_social, c.ruc, c.direccion,
       comp.tipo, comp.metodo_pago
FROM ventas v
JOIN clientes c ON v.id_cliente = c.id
JOIN comprobantes comp ON comp.id_venta = v.id
WHERE v.id = $id_venta
";
$venta = $conexion->query($venta_sql)->fetch_assoc();

if (!$venta) {
    die("❌ Venta no encontrada.");
}

// Obtener detalles de productos
$detalle_sql = "
SELECT p.nombre, d.cantidad, d.subtotal
FROM detalle_venta d
JOIN productos p ON d.id_producto = p.id
WHERE d.id_venta = $id_venta
";
$detalles = $conexion->query($detalle_sql);

// HTML para el PDF
$html = '
<style>
body { font-family: Arial; font-size: 13px; }
h1 { text-align: center; font-size: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { border: 1px solid #aaa; padding: 6px; text-align: left; }
.total { text-align: right; font-weight: bold; }
</style>

<h1>FACTURA</h1>
<p><strong>Empresa:</strong> '.$venta['razon_social'].'</p>
<p><strong>RUC:</strong> '.$venta['ruc'].'</p>
<p><strong>Dirección:</strong> '.$venta['direccion'].'</p>
<p><strong>Fecha:</strong> '.$venta['fecha'].'</p>

<table>
  <thead>
    <tr>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Subtotal (S/)</th>
    </tr>
  </thead>
  <tbody>';
while ($fila = $detalles->fetch_assoc()) {
    $html .= '
    <tr>
      <td>'.$fila['nombre'].'</td>
      <td>'.$fila['cantidad'].'</td>
      <td>'.number_format($fila['subtotal'], 2).'</td>
    </tr>';
}
$html .= '
  </tbody>
</table>

<p class="total">Total: S/ '.number_format($venta['total'], 2).'</p>
<p class="total">Método de pago: '.ucfirst($venta['metodo_pago']).'</p>
<p class="total">Tipo de comprobante: '.ucfirst($venta['tipo']).'</p>
<p style="text-align:center; margin-top: 30px;">Gracias por su compra</p>
';

// Opciones de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Generar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar el archivo
$dompdf->stream("factura_venta_$id_venta.pdf", ["Attachment" => true]);
exit;
?>
