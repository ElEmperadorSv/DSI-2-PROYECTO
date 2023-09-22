<?php
include "../Controlador/db_connection.php";

// Consulta para obtener los estados desde la base de datos
$query = "SELECT DISTINCT estado_ct FROM clientes_dsi";
$result = $conn->query($query);

$estados = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estados[] = $row['estado_ct'];
    }
}

echo json_encode($estados);
