<!--Controlador de Inicio de Sesión-->
<?php include '../Controlador/sesion.php'; ?>

<head>
    <title>Permiso Denegado</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> DASHBOARD </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="row">
                    <div class="col-md-5 mx-auto">
                        <div class="card">
                            <div class="card-header text-center bg-primary">
                                <h4 class="text-white">No tienes permisos sobre el Menú</h4>
                            </div>
                            <div class="card-body" style="text-align: center;">
                                <a href="../Vista/home.php" class="btn btn-danger btn-block">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>

        <?php include '../Modelo/o_scrips_generales.php'; ?>
</body>

</html>