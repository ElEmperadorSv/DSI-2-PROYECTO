<?php
// Incluye el archivo de conexión a la base de datos
include "../Controlador/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filtroDUI = $_POST["filtroDUI"];
    $filtroNombre = $_POST["filtroNombre"];
    $filtroEmail = $_POST["filtroEmail"];

    // Construye la consulta SQL con los filtros
    $query = "SELECT * FROM clientes_dsi WHERE 1";

    if (!empty($filtroDUI)) {
        $query .= " AND dui_ct LIKE '%" . $filtroDUI . "%'";
    }

    if (!empty($filtroNombre)) {
        $query .= " AND nombre_ct LIKE '%" . $filtroNombre . "%'";
    }

    if (!empty($filtroEmail)) {
        $query .= " AND email_ct LIKE '%" . $filtroEmail . "%'";
    }

    // Realiza la consulta a la base de datos
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $clientes = array();

        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }

        // Devuelve los datos de clientes en formato JSON
        echo json_encode($clientes);
    } else {
        // Devuelve un mensaje si no se encuentran resultados
        echo json_encode(array("message" => "No se encontraron resultados"));
    }
} else {
    // Devuelve un mensaje de error si la solicitud no es de tipo POST
    echo json_encode(array("message" => "Error en la solicitud"));
}

// Cierra la conexión a la base de datos
$conn->close();
