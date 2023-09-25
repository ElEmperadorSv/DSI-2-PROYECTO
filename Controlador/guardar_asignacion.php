<?php
require_once "../Controlador/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol = $_POST['rol'];
    $vistas = $_POST['vistas'];

    // Limpiar y escapar los datos para evitar inyección de SQL (usar consultas preparadas si es posible)
    $rol = mysqli_real_escape_string($conn, $rol);

    // Eliminar las asignaciones anteriores para este rol
    $deleteQuery = "DELETE FROM vistas_dsi WHERE rol = '$rol'";
    mysqli_query($conn, $deleteQuery);

    // Insertar las nuevas asignaciones
    foreach ($vistas as $vista) {
        $vista = mysqli_real_escape_string($conn, $vista);
        $insertQuery = "INSERT INTO vistas_dsi (rol, vista) VALUES ('$rol', '$vista')";
        mysqli_query($conn, $insertQuery);
    }

    mysqli_close($conn);
    http_response_code(200);  // Todo fue exitoso
} else {
    http_response_code(400);  // Bad Request
}
