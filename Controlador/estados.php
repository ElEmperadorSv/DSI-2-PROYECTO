<?php
include '../Controlador/db_connection.php';

// Consulta para obtener los estados desde la base de datos
$sql = "SELECT DISTINCT estado FROM usuarios_dsi";
$result = $conn->query($sql);

$estados = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estados[] = $row['estado'];
    }
}

// Devuelve los estados en formato JSON
echo json_encode($estados);

// Cierra la conexión a la base de datos
$conn->close();
