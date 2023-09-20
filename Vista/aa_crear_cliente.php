<!--Controlador de Inicio de Sesión-->
<?php include '../Controlador/sesion.php'; ?>

<head>
    <title>Crear Cliente</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Crear un nuevo Cliente</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> DASHBOARD </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#myModal">
                    Mostrar Modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-m modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cliente Nuevo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form>
                                    <div class="mb-3 row">
                                        <label for="dui" class="col-sm-2 col-form-label">DUI</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="dui">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre">
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="apellido">
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="fechaNacimiento" class="col-sm-2 col-form-label">Fecha de Nacimiento</label>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" id="fechaNacimiento">
                                        </div>
                                        <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                        <div class="col-sm-4">
                                            <input type="tel" class="form-control" id="telefono">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Guardar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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