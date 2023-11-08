<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('db_connection.php');
    $_POST = json_decode(file_get_contents('php://input'), true);

    $username = $_POST['username'];
    $query = "SELECT * FROM usuarios_dsi WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);


    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        //generar codigo y enviar correo con el codigo
        $response = [
            "msg" => "El usuario $username si existe",
            "success" => true,
        ];

        $data = $result->fetch_array();

        //generar codigo 
        $n = 6;
        $finalCode = substr(bin2hex(random_bytes($n)), 0, 6);

        //guardar codigo en la base de datos
        $updateQuery = "UPDATE usuarios_dsi SET codigo_recuperacion= ? WHERE username = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("ss", $finalCode, $username);
        $resultUpdate = $stmtUpdate->execute();
        if ($resultUpdate == 1) {
            $mailResponse = send($data['email'], $finalCode);
            // echo $mailResponse;
            echo (json_encode($response));
        }
    } else {
        //enviar mensaje de que el usuario no existe
        $response = [
            "msg" => "El usuario $username no existe",
            "success" => false,
        ];

        echo (json_encode($response));
    }
}

function send($mail, $code)
{
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("DSI_ONE@ues.edu.sv", "Equipo Desarrollo");
    $email->setSubject("Correo de Recuperacion");
    $email->addTo($mail, "User");
    $email->addContent(
        "text/html",
        "
        <p>Usa el siguiente código para recuperar tu clave</p>
        <br>
        <strong>$code</strong>
        "
    );
    $sendgrid = new \SendGrid('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');
    

    try {
        $response = $sendgrid->send($email);

        if ($response->statusCode() === 202) {
            return 'Correo enviado con éxito';
        } else {
            return 'Error en el envío del correo: ' . $response->statusCode() . ' - ' . $response->body();
        }
    } catch (Exception $e) {
        return 'Excepción atrapada: ' . $e->getMessage();
    }
}

