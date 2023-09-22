<?php
include "../Controlador/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_pd'])) {
        $id = $_POST['id_pd'];

        // Consulta para obtener los datos del cliente por ID
        $query = "SELECT * FROM productos_dsi WHERE id_pd = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            echo json_encode($producto);
        }
    }
}