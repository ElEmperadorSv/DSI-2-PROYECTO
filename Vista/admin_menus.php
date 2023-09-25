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
                                                        echo "<option value='$rol_nombre'>$rol_nombre</option>"; // Modified this line
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

                <div class="card-body" id="vistas-disponibles" style="display: none;">
                    <form id="vistas-form">
                        <ul class="list-group">
                            <?php
                            // Definir las vistas disponibles desde la base de datos o donde las tengas almacenadas
                            $vistas_disponibles = [
                                'Vista A' => 'Inicio',
                                'Vista B' => 'Gestionar Usuarios',
                                'Vista C' => 'Gestionar Rol',
                                'Vista D' => 'Gestionar Vistas',
                                'Vista E' => 'Gestionar Productos',
                                'Vista F' => 'Gestionar Clientes',
                                'Vista G' => 'Gestionar Créditos'
                            ];

                            // Mostrar cada vista disponible como un checkbox
                            foreach ($vistas_disponibles as $key => $value) {
                                echo "<li class='list-group-item'>
                        <div class='form-check'>
                            <input class='form-check-input' type='checkbox' name='vistas[]' value='$key' id='$key'>
                            <label class='form-check-label' for='$key'>$value</label>
                        </div>
                    </li>";
                            }
                            ?>
                        </ul>
                        <button type="submit" class="btn btn-primary mx-2 my-1">Guardar Asignación</button>
                        <button type="button" class="btn btn-secondary mx-2 my-1" onclick="cancelarAsignacion()">Cancelar</button>
                    </form>
                </div>


                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>




        <?php include '../Modelo/o_scrips_generales.php'; ?>

        <script>
            document.getElementById('buscar-button').addEventListener('click', function() {
                document.getElementById('vistas-disponibles').style.display = 'block';
                document.getElementById('card-footer').style.display = 'block';
            });

            function cancelarAsignacion() {
                document.getElementById('vistas-disponibles').style.display = 'none';
                document.getElementById('card-footer').style.display = 'none';
                document.getElementById('roles').selectedIndex = 0;
            }

            document.getElementById('vistas-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const selectedRole = document.getElementById('roles').value;
                const selectedViews = [];
                const checkboxes = document.querySelectorAll('input[name="vistas[]"]:checked');

                checkboxes.forEach(function(checkbox) {
                    selectedViews.push(checkbox.value);
                });

                // Enviar los datos al servidor utilizando AJAX
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Asignación guardada exitosamente.');
                        } else {
                            alert('Hubo un error al guardar la asignación.');
                        }
                    }
                };

                const formData = new FormData();
                formData.append('rol', selectedRole);
                selectedViews.forEach(function(view) {
                    formData.append('vistas[]', view);
                });

                // Cambia la URL a la que enviar la solicitud según tu configuración
                xhr.open('POST', '../Controlador/guardar_asignacion.php'); // Cambia la URL según tu configuración
                xhr.send(formData);
            });
        </script>
</body>

</html>