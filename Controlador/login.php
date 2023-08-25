<?php
    session_start();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");


    if(isset($_SESSION['username'])) {
        header("Location: ../Vista/home.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('db_connection.php');

        $username = $_POST['username'];
        $password = $_POST['password'];
        // Hash de la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Para verificar la contraseña
        if (password_verify($password, $hashedPassword)) {
            // La contraseña es válida
        } else {
            // La contraseña es incorrecta
}

        $query = "SELECT * FROM usuarios_dsi WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['username'] = $username;
            header("Location: ../Vista/home.php");
            exit();
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
?>
