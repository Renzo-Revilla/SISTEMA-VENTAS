<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("php/conexion.php");

$nombre     = trim($_POST['nombre']);
$apellido   = trim($_POST['apellido']);
$dni        = trim($_POST['dni']);
$rol        = trim($_POST['rol']);
$usuario    = trim($_POST['nuevo_usuario']);
$clave      = $_POST['nueva_clave'];
$confirmar  = $_POST['confirmar_clave'];

// Validaciones
if (empty($nombre) || empty($apellido) || empty($dni) || empty($rol) || empty($usuario) || empty($clave) || empty($confirmar)) {
    echo "<script>alert('❌ Todos los campos son obligatorios.'); window.location.href='registro.html';</script>";
    exit();
}

if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
    echo "<script>alert('❌ El nombre solo puede contener letras.'); window.location.href='registro.html';</script>";
    exit();
}

if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $apellido)) {
    echo "<script>alert('❌ El apellido solo puede contener letras.'); window.location.href='registro.html';</script>";
    exit();
}

if (!preg_match("/^\d{8}$/", $dni)) {
    echo "<script>alert('❌ El DNI debe tener 8 dígitos.'); window.location.href='registro.html';</script>";
    exit();
}

if ($clave !== $confirmar) {
    echo "<script>alert('❌ Las contraseñas no coinciden.'); window.location.href='registro.html';</script>";
    exit();
}

// Validar usuario único
$verifica = $conexion->query("SELECT * FROM usuarios WHERE usuario = '$usuario'");
if ($verifica && $verifica->num_rows > 0) {
    echo "<script>alert('⚠️ El usuario ya existe.'); window.location.href='registro.html';</script>";
    exit();
}

// Guardar usuario
$sql = "INSERT INTO usuarios (nombre, apellido, dni, rol, usuario, clave)
        VALUES ('$nombre', '$apellido', '$dni', '$rol', '$usuario', '$clave')";

if ($conexion->query($sql)) {
    echo "<script>alert('✅ Usuario registrado correctamente.'); window.location.href='index.html';</script>";
} else {
    echo "<script>alert('❌ Error al registrar el usuario: " . $conexion->error . "'); window.location.href='registro.html';</script>";
}
?>
