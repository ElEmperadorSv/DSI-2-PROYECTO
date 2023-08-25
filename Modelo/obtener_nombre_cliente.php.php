<?php
include "../Controlador/db_connection.php";

// Verificar si se ha enviado el DUI del cliente
if (isset($_POST['dui'])) {
    $dui = $_POST['dui'];

    // Consultar la tabla clientes_dsi para obtener el nombre y apellido del cliente correspondiente al DUI
    $query = "SELECT nombre, apellido FROM clientes_dsi WHERE dui = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $dui);
    $stmt->execute();
    $stmt->bind_result($nombre, $apellido); // Agrega $apellido a bind_result()
    $stmt->fetch();
    $stmt->close();

    // Devolver el nombre completo del cliente como respuesta
    $nombreCompleto = $nombre . " " . $apellido;
    echo $nombreCompleto;
}
