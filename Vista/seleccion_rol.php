<?php
session_start();
require_once('../Controlador/db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener los roles disponibles desde la base de datos
$usuario = $_SESSION['username'];
$query = "SELECT rol FROM usuarios_dsi WHERE username = '$usuario'";
$result = $conn->query($query);

// Verificar si se obtuvieron resultados
if ($result && $result->num_rows > 0) {
    $roles_disponibles = array();
    while ($row = $result->fetch_assoc()) {
        $roles_disponibles[] = $row['rol'];
    }
} else {
    $roles_disponibles = array(); // No se encontraron roles
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Rol</title>

    <!-- Incluir Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Complementos/CSS/style.css">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />
</head>

<body class="bg-primary">
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Selección de Rol</h3>
                        </div>
                        <div class="card-body">
                            <form action="../Vista/home.php" method="POST">
                                <div class="form-group">
                                    <label for="rol">Selecciona tu rol:</label>
                                    <select class="form-select" id="rol" name="rol">
                                    <option value="" disabled selected>Seleccionar Rol</option>
                                        <?php
                                        // Mostrar los roles disponibles en el select
                                        foreach ($roles_disponibles as $rol) {
                                            echo "<option value='$rol'>$rol</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Aceptar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script src="../Complementos/JS/recuperacion_clave.js"></script>
</body>

</html>