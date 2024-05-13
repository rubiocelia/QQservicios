<?php
// Iniciar sesión
session_start();

// Destruir todas las variables de sesión
session_destroy();

// Redirigir al usuario al formulario de inicio de sesión
header("Location: ../index.php");
exit();
?>
