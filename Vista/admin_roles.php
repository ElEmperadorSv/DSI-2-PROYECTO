<!--Controlador de Inicio de Sesión-->
<?php include '../Controlador/sesion.php';

// Función para validar los campos requeridos
function validarCampos($campos)
{
    foreach ($campos as $campo) {
        if (empty($_POST[$campo])) {
            return false;
        }
    }
    return true;
}

// Verificar si se ha enviado el formulario de agregar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitGuardar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('nombre_rol', 'descripcion_rol');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del cliente desde el formulario
        $nombre_rol = $_POST['nombre_rol'];
        $descripcion_rol = $_POST['descripcion_rol'];

        // Consulta de inserción
        $query = "INSERT INTO roles_usuarios (nombre_rol, descripcion_rol) VALUES (?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("ss", $nombre_rol, $descripcion_rol);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción fue exitosa
            if ($stmt->affected_rows > 0) {
                // La inserción fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de roles
                header("Location: ../Vista/admin_roles.php");
                exit();
            } else {
                // Ocurrió un error al insertar el registro
                echo "Error al insertar el registro en la base de datos.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            // Ocurrió un error al preparar la consulta
            echo "Error al preparar la consulta.";
        }

        // Cerrar la conexión
        $conn->close();
    }
}

// Verificar si se ha enviado el formulario de actualizar cliente
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitActualizar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('id', 'nombre_rol', 'descripcion_rol', 'estado');
    if (validarCampos($camposRequeridos)) {
        // Obtener el ID del rol a actualizar desde el formulario
        $id = $_POST['id'];

        // Obtener los datos actualizados del cliente desde el formulario
        $nombre_rol = $_POST['nombre_rol'];
        $descripcion_rol = $_POST['descripcion_rol'];
        $estado = $_POST['estado'];

        // Consulta de actualización
        $query = "UPDATE roles_usuarios SET nombre_rol = ?, descripcion_rol = ?, estado = ? WHERE id = ?";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("sssi", $nombre_rol, $descripcion_rol, $estado, $id);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                // La actualización fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de roles
                header("Location: ../Vista/admin_roles.php");
                exit();
            } else {
                // Ocurrió un error al actualizar el registro
                echo "Error al actualizar el registro en la base de datos.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            // Ocurrió un error al preparar la consulta
            echo "Error al preparar la consulta.";
        }

        // Cerrar la conexión
        $conn->close();
    }
}

?>

<head>
    <title>Gestión Roles</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Gestión de Roles de Usuarios</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> Dashboard </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Roles de Usuario
                        <div style="float: right;">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRolModal"><i class="fa-solid fa-circle-plus"></i> Nuevo Rol</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Rol</th>
                                    <th>Descripción del Rol</th>
                                    <th>Estado</th>
                                    <th>Función</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM roles_usuarios";
                                $result = $conn->query($query);
                                // Verificar si se encontraron resultados
                                if ($result->num_rows > 0) {
                                    // Iterar sobre los resultados y generar las filas de la tabla
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['nombre_rol'] . "</td>";
                                        echo "<td>" . $row['descripcion_rol'] . "</td>";
                                        echo "<td>" . $row['estado'] . "</td>";
                                        echo '<td>
                                                <button class="btn btn-primary" onclick="cargarDatosRol(' . $row['id'] . ')" data-bs-toggle="modal" data-bs-target="#editRolModal">Editar <i class="fas fa-pencil-alt" style="color: white;"></i></button>
                                            </td>';
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No se encontraron registros en la tabla roles_usuarios.</td></tr>";
                                }
                                // Cerrar la conexión
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>

        <?php include '../Modelo/o_scrips_generales.php'; ?>

        <!------------------------ Modal: Crear Nuevo Rol ------------------------>
        <div class="modal fade" id="addRolModal" tabindex="-1" aria-labelledby="addRolModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRolModal">Crear Nuevo Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                                <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion_rol" class="form-label">Descripción del Rol</label>
                                <input type="text" class="form-control" id="descripcion_rol" name="descripcion_rol" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="submitGuardar">Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!------------------------ Modal: Crear Nuevo Rol ------------------------>

        <!------------------------ Modal: Editar Información de Rol ------------------------>
        <div class="modal fade" id="editRolModal" tabindex="-1" aria-labelledby="editRolModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRolModal">Editar la información de Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" id="id" name="id">

                            <div class="mb-3">
                                <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                                <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_rol" class="form-label">Descripción del Rol</label>
                                <input type="text" class="form-control" id="descripcion_rol" name="descripcion_rol" required>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <input type="submit" class="btn btn-primary" name="submitActualizar" value="Guardar Cambios">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!------------------------ Modal: Editar Información de Rol ------------------------>

        <!------------------------ Obtener Datos de Rol ------------------------>
        <script>
            function cargarDatosRol(id) {
                $.ajax({
                    url: '../Controlador/datos_roles.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // Parsear la respuesta JSON
                        var rol = JSON.parse(response);

                        // Llenar los campos del formulario en el modal con los datos del cliente
                        $('#editRolModal #id').val(rol.id);
                        $('#editRolModal #nombre_rol').val(rol.nombre_rol);
                        $('#editRolModal #descripcion_rol').val(rol.descripcion_rol);

                        // Cargar los valores posibles de estado en el combo box
                        var selectEstado = $('#editRolModal #estado');
                        selectEstado.empty(); // Limpiar opciones existentes

                        // Definir los estados disponibles
                        var estadosDisponibles = ["ACTIVO", "INACTIVO"];

                        // Iterar sobre los estados y agregarlos al combo box
                        for (var i = 0; i < estadosDisponibles.length; i++) {
                            var estado = estadosDisponibles[i];
                            var selected = (estado === rol.estado) ? 'selected' : '';
                            selectEstado.append('<option value="' + estado + '" ' + selected + '>' + estado + '</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        </script>
        <!------------------------ Obtener Datos de Rol ------------------------>

</body>

</html>