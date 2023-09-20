<?php
include "../Controlador/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Consulta para obtener los datos del cliente por ID
        $query = "SELECT * FROM roles_usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $rol = $result->fetch_assoc();
            echo json_encode($rol);
        }
    }
}
