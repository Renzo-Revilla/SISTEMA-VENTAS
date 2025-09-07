<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Verifica existencia en base de datos
$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $_SESSION['usuario'] = $usuario;
    header("Location: ../menu.php");
    exit();
} else {
    echo "<script>
            alert('❌ Usuario o contraseña incorrectos.');
            window.location.href = '../index.html';
          </script>";
}
?>
