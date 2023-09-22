<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('db_connection.php');
    $_POST = json_decode(file_get_contents('php://input'), true);

    $username = $_POST['username'];
    $code = $_POST['code'];
    $query = "SELECT * FROM usuarios_dsi WHERE username = ? AND codigo_recuperacion = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $code);


    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $response = [
            "msg" => "El codigo es correcto",
            "success" => true,
        ];

        echo (json_encode($response));
    } else {
        $response = [
            "msg" => "El codigo es incorrecto",
            "success" => false,
        ];

        echo (json_encode($response));
    }
}
