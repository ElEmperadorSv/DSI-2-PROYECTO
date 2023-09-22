<?php
include '../Controlador/db_connection.php';

// Consulta para obtener los roles
$query = "SELECT nombre_rol FROM roles_usuarios";
$result = $conn->query($query);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    $nombre_rol = array();
    while ($row = $result->fetch_assoc()) {
        $nombre_rol[] = $row;
    }
    echo json_encode($nombre_rol);
} else {
    echo json_encode(array());
}

// Cerrar la conexión
$conn->close();
