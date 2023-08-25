<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_dsi_one";

// Crear una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si hay algún error de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
