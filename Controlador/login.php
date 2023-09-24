<?php
session_start();
require_once('../Controlador/db_connection.php');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

if (isset($_SESSION['username'])) {
    header("Location: ../Vista/seleccion_rol.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('db_connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios_dsi WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            header("Location: ../Vista/seleccion_rol.php");
            exit();
        } else {
            $message = "Usuario o contraseña incorrectos. Verifica que tus datos sean correctos.";
            $_SESSION['login_error'] = $message;
            header("Location: ../index.php");
            exit();
        }
    } else {
        $message = "Usuario o contraseña incorrectos. Verifica que tus datos sean correctos.";
        $_SESSION['login_error'] = $message;
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
