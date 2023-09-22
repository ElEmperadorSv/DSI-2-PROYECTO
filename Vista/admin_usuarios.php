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

// Verificar si se ha enviado el formulario de agregar un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitGuardar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('username', 'password', 'carne', 'email', 'rol');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del usuario desde el formulario
        $username = $_POST['username'];
        $password = $_POST['password'];
        $carne = $_POST['carne'];
        $email = $_POST['email'];
        $rol = $_POST['rol'];

        // Consulta de inserción
        $query = "INSERT INTO usuarios_dsi (username, password, carne, email, rol) VALUES (?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("sssss", $username, $password, $carne, $email, $rol);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción fue exitosa
            if ($stmt->affected_rows > 0) {
                // La inserción fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de roles
                header("Location: ../Vista/admin_usuarios.php");
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

// Verificar si se ha enviado el formulario de actualizar usuario
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitActualizar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('id', 'username', 'carne', 'email', 'estado', 'rol');
    if (validarCampos($camposRequeridos)) {
        // Obtener el ID del usuario a actualizar desde el formulario
        $id = $_POST['id'];

        // Obtener los datos actualizados del cliente desde el formulario
        $username = $_POST['username'];
        $carne = $_POST['carne'];
        $email = $_POST['email'];
        $estado = $_POST['estado'];
        $rol = $_POST['rol'];

        // Consulta de actualización
        $query = "UPDATE usuarios_dsi SET username = ?, carne = ?, email = ?, estado = ?, rol = ? WHERE id = ?";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("sssssi", $username, $carne, $email, $estado, $rol, $id);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                // La actualización fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de roles
                header("Location: ../Vista/admin_usuarios.php");
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
    <title>Gestión Usuarios</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Gestión de Usuarios de Sistema</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> Dashboard </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Usuarios con acceso a Sistema
                        <div style="float: right;">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUsuarioModal"><i class="fa-solid fa-circle-plus"></i> Nuevo Usuario</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Carné</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                    <th>Rol</th>
                                    <th>Función</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM usuarios_dsi";
                                $result = $conn->query($query);
                                // Verificar si se encontraron resultados
                                if ($result->num_rows > 0) {
                                    // Iterar sobre los resultados y generar las filas de la tabla
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['carne'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['rol'] . "</td>";
                                        echo "<td>" . $row['estado'] . "</td>";
                                        echo '<td>
                                                <button class="btn btn-primary" onclick="cargarDatosUsuario(' . $row['id'] . ')" data-bs-toggle="modal" data-bs-target="#editUsuarioModal">Editar <i class="fas fa-pencil-alt" style="color: white;"></i></button>
                                            </td>';
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No se encontraron registros en la tabla usuarios_dsi.</td></tr>";
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

        <!------------------------ Modal: Crear Nuevo Usuario ------------------------>
        <div class="modal fade" id="addUsuarioModal" tabindex="-1" aria-labelledby="addUsuarioModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUsuarioModal">Crear Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña Provisional</label>
                                <input type="text" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="carne" class="form-label">Carné</label>
                                <input type="text" class="form-control" id="carne" name="carne" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-select" id="rol" name="rol" required>
                                </select>
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
        <!------------------------ Modal: Crear Nuevo Usuario ------------------------>

        <!------------------------ Modal: Editar Información de Usuario ------------------------>
        <div class="modal fade" id="editUsuarioModal" tabindex="-1" aria-labelledby="editUsuarioModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUsuarioModal">Editar la información de Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" id="id" name="id">

                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="carne" class="form-label">Carné</label>
                                <input type="text" class="form-control" id="carne" name="carne" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-select" id="rol" name="rol" required>
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
        <!------------------------ Modal: Editar Información de Usuario ------------------------>


        <script>
            $(document).ready(function() {
                // Función para cargar las opciones de roles
                function cargarOpcionesRoles() {
                    $.ajax({
                        url: '../Controlador/roles.php',
                        method: 'POST',
                        success: function(response) {
                            var roles = JSON.parse(response);
                            var selectRol = $('#addUsuarioModal #rol');
                            selectRol.empty(); // Limpiar opciones existentes
                            for (var i = 0; i < roles.length; i++) {
                                selectRol.append('<option value="' + roles[i].nombre_rol + '">' + roles[i].nombre_rol + '</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
                cargarOpcionesRoles(); // Cargar opciones de roles al cargar la página
            });
        </script>

        <script>
            function cargarDatosUsuario(id) {
                $.ajax({
                    url: '../Controlador/datos_usuarios.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        var user = JSON.parse(response);

                        $('#editUsuarioModal #id').val(user.id);
                        $('#editUsuarioModal #username').val(user.username);
                        $('#editUsuarioModal #carne').val(user.carne);
                        $('#editUsuarioModal #email').val(user.email);
                        $('#editUsuarioModal #estado').val(user.estado);
                        $('#editUsuarioModal #rol').val(user.rol);

                        // Cargar los valores posibles de estado desde la base de datos
                        $.ajax({
                            url: '../Controlador/estados.php',
                            method: 'GET',
                            success: function(response) {
                                var estados = JSON.parse(response);
                                var selectEstado = $('#editUsuarioModal #estado');
                                selectEstado.empty(); // Limpiar opciones existentes
                                for (var i = 0; i < estados.length; i++) {
                                    var estado = estados[i];
                                    var selected = (estado === user.estado) ? 'selected' : '';
                                    selectEstado.append('<option value="' + estado + '" ' + selected + '>' + estado + '</option>');
                                }

                                // Agregar la opción "INACTIVO" si no está ya en la lista
                                if (!estados.includes('INACTIVO')) {
                                    selectEstado.append('<option value="INACTIVO">INACTIVO</option>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        });

                        // Cargar los valores posibles de rol desde la base de datos
                        $.ajax({
                            url: '../Controlador/roles.php',
                            method: 'GET',
                            success: function(response) {
                                var roles = JSON.parse(response);
                                var selectRol = $('#editUsuarioModal #rol');
                                selectRol.empty(); // Limpiar opciones existentes
                                for (var i = 0; i < roles.length; i++) {
                                    var role = roles[i].nombre_rol;
                                    var selected = (role === user.rol) ? 'selected' : '';
                                    selectRol.append('<option value="' + role + '" ' + selected + '>' + role + '</option>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        </script>



</body>

</html>