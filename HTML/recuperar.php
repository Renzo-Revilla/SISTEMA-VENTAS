<?php
include("php/conexion.php");

$dni = trim($_POST['dni']);
$nueva = $_POST['nueva_clave'];
$confirmar = $_POST['confirmar_clave'];

if (empty($dni) || empty($nueva) || empty($confirmar)) {
    echo "<script>alert('❌ Todos los campos son obligatorios.'); window.location.href='recuperar.html';</script>";
    exit();
}

if ($nueva !== $confirmar) {
    echo "<script>alert('❌ Las contraseñas no coinciden.'); window.location.href='recuperar.html';</script>";
    exit();
}

$resultado = $conexion->query("SELECT * FROM usuarios WHERE dni = '$dni'");

if ($resultado && $resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();
    $userLogin = $usuario['usuario'];

    $conexion->query("UPDATE usuarios SET clave = '$nueva' WHERE dni = '$dni'");
    echo "<script>alert('✅ Contraseña actualizada para el usuario: $userLogin'); window.location.href='index.html';</script>";
} elseif ($resultado && $resultado->num_rows > 1) {
    echo "<script>alert('⚠️ Hay más de un usuario con ese DNI. Contacta al administrador.'); window.location.href='recuperar.html';</script>";
} else {
    echo "<script>alert('❌ No se encontró ningún usuario con ese DNI.'); window.location.href='recuperar.html';</script>";
}
?>

