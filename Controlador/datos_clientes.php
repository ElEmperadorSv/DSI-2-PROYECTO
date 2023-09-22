<?php
include "../Controlador/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_ct'])) {
        $id_ct = $_POST['id_ct'];

        // Consulta para obtener los datos del cliente por ID
        $query = "SELECT * FROM clientes_dsi WHERE id_ct = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_ct);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode($user);
        }
    }
}
