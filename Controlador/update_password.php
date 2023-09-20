<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('db_connection.php');
    $_POST = json_decode(file_get_contents('php://input'), true);

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "UPDATE usuarios_dsi SET codigo_recuperacion = NULL, `password` = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $password, $username);

    $result = $stmt->execute();

    if ($result == 1) {
        $response = [
            "msg" => "La clave ha sido actualizada",
            "success" => true,
        ];

        echo (json_encode($response));
    } else {
        $response = [
            "msg" => "Ha ocurrido un error al actualizar la clave. Intente nuevamente.",
            "success" => false,
        ];

        echo (json_encode($response));
    }
}
