<?php
//Conexión a la base de datos
include "../Controlador/db_connection.php";

//Iniciando Sesión
session_start();

// Verificar si la sesión no está activa
if (!isset($_SESSION['username'])) {
    // Redireccionar al usuario a la página de inicio de sesión
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">