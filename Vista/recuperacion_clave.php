<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación</title>

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
                            <h3 class="text-center font-weight-light my-4">Recuperación</h3>
                        </div>
                        <div class="card-body">
                            <div id="error" class="alert alert-danger d-none">Error</div>
                            <div id="success" class="alert alert-success d-none">Exito</div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="username" name="username" type="text" placeholder="Ingrese su usuario" required autofocus>
                                <label for="username">Usuario</label>
                            </div>
                            <div class="form-floating mb-3 d-none" id="recoverySection">
                                <input class="form-control" id="recovery_code" name="recovery_code" type="text" placeholder="Ingrese el codigo enviado a su correo" required autofocus>
                                <label for="recovery_code">Codigo</label>
                            </div>

                            <div class="form-floating mb-3 d-none" id="newPasswordSection">
                                <div class="form-group">
                                    <input class="form-control my-2" id="password" name="password" type="password" placeholder="Ingrese su nueva contraseña" required>
                                    <input class="form-control" id="password2" name="password2" type="password" placeholder="Repita la contraseña" required>

                                </div>

                            </div>

                            <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                                <!--<a class="small" href="password.html">¿Olvidaste tu contraseña?</a>-->
                                <button id="btnEnviarCorreo" type="submit" class="btn btn-primary">Enviar Correo de Recuperacion</button>
                                <button id="btnVerificarCodigo" type="submit" class="btn btn-primary d-none">Verificar Codigo</button>
                                <button id="btnActualizarClave" type="submit" class="btn btn-primary d-none">Actualizar Clave</button>
                                <a id="linkLogin" href="../index.php" type="submit" class="btn btn-success d-none">Ir al login</a>
                            </div>
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