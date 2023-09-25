<!--Controlador de Inicio de Sesión-->
<?php include '../Controlador/sesion.php'; ?>

<head>
    <title>Gestión Menús</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Mantenimiento de Menú por Rol de usuario</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> DASHBOARD </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="container mt-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i> Selecciona un rol
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="roles" class="col-md-2 col-form-label">Rol</label>
                                        <div class="col-md-6">
                                            <select id="roles" class="form-select">
                                                <option value="" selected disabled>Seleccionar Rol</option>
                                                <?php
                                                // Incluir el archivo de conexión a la base de datos
                                                require_once "../Controlador/db_connection.php";

                                                // Consulta los datos de la tabla roles_usuarios
                                                $sql = "SELECT nombre_rol FROM roles_usuarios";
                                                $result = mysqli_query($conn, $sql);

                                                // Verifica si se encontraron registros
                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $rol_nombre = $row['nombre_rol'];
                                                        echo "<option value='$rol_id'>$rol_nombre</option>";
                                                    }
                                                } else {
                                                    echo "<option value='' disabled>No se encontraron roles</option>";
                                                }

                                                // Cierra la conexión a la base de datos
                                                mysqli_close($conn);
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button id="buscar-button" class="btn btn-primary w-100">Buscar</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="col-md-6 mb-4">
                        <div class="card" style="max-height: 200px;">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i> Vistas disponibles
                            </div>
                            <div class="card-body" id="vistas-disponibles" style="display: none;">
                                <form>
                                    <ul class="list-group" id="vistasList">
                                        <?php
                                        // Incluir el archivo de conexión a la base de datos
                                        require_once "../Controlador/db_connection.php";

                                        // Consultar datos
                                        $sql = "SELECT * FROM vistas_dsi";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Procesar los resultados
                                            while ($row = $result->fetch_assoc()) {
                                                echo "ID: " . $row["id"] . " - Nombre: " . $row["nombre"] . "<br>";
                                            }
                                        } else {
                                            echo "0 resultados";
                                        }

                                        // Cerrar la conexión
                                        $conn->close();
                                        ?>
                                    </ul>
                                </form>
                            </div>
                            <div class="card-footer" id="card-footer" style="display: none;">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <button class="btn btn-primary mx-2 my-1">Guardar Asignación</button>
                                    <button class="btn btn-secondary mx-2 my-1" onclick="cancelarAsignacion()">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>




        <?php include '../Modelo/o_scrips_generales.php'; ?>

        <script>
            /*//Body Card
            document.getElementById('buscar-button').addEventListener('click', function() {
                document.getElementById('vistas-disponibles').style.display = 'block';
                document.getElementById('card-footer').style.display = 'block';
            });

            // Footer Card
            function cancelarAsignacion() {
                document.getElementById('vistas-disponibles').style.display = 'none';
                document.getElementById('card-footer').style.display = 'none';
                document.getElementById('roles').selectedIndex = 0;
            }
            */
            // Obtener el rol seleccionado y mostrar las vistas correspondientes
            document.getElementById('buscar-button').addEventListener('click', function() {
                const rolSeleccionado = document.getElementById('roles').value;
                // Aquí puedes hacer algo con el rol seleccionado, como cargar las vistas correspondientes.
                console.log('Rol seleccionado:', rolSeleccionado);

                // Mostrar las vistas disponibles
                document.getElementById('vistas-disponibles').style.display = 'block';
                document.getElementById('card-footer').style.display = 'block';
            });

            // Restaurar selección de rol y ocultar vistas al cancelar
            function cancelarAsignacion() {
                document.getElementById('vistas-disponibles').style.display = 'none';
                document.getElementById('card-footer').style.display = 'none';
                document.getElementById('roles').selectedIndex = 0;
            }

            // Manejar el evento de cambio de selección de checkbox
            const checkboxes = document.querySelectorAll('input[name="vista"]');
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });

            function handleCheckboxChange() {
                const selectedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
                const cardFooter = document.getElementById('card-footer');
                if (selectedCheckboxes.length > 0) {
                    cardFooter.style.display = 'block';
                } else {
                    cardFooter.style.display = 'none';
                }
            }
        </script>
</body>

</html>