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

// Verificar si se ha enviado el formulario de agregar un nuevo cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitGuardar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('dui_ct', 'nombre_ct', 'apellido_ct', 'fecha_nac_ct', 'email_ct', 'telefono_ct', 'direccion_ct', 'estado_ct');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del cliente desde el formulario
        $dui_ct = $_POST['dui_ct'];
        $nombre_ct = $_POST['nombre_ct'];
        $apellido_ct = $_POST['apellido_ct'];
        $fecha_nac_ct = $_POST['fecha_nac_ct'];
        $email_ct = $_POST['email_ct'];
        $telefono_ct = $_POST['telefono_ct'];
        $direccion_ct = $_POST['direccion_ct'];
        $estado_ct = $_POST['estado_ct'];

        // Consulta de inserción
        $query = "INSERT INTO clientes_dsi (dui_ct, nombre_ct, apellido_ct, fecha_nac_ct, email_ct, telefono_ct, direccion_ct, estado_ct) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("ssssssss", $dui_ct, $nombre_ct, $apellido_ct, $fecha_nac_ct, $email_ct, $telefono_ct, $direccion_ct, $estado_ct);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción fue exitosa
            if ($stmt->affected_rows > 0) {
                // La inserción fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de clientes
                header("Location: ../Vista/aa_crear_cliente.php");
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitActualizar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('id_ct', 'dui_ct', 'nombre_ct', 'apellido_ct', 'fecha_nac_ct', 'email_ct', 'telefono_ct', 'direccion_ct', 'estado_ct');
    if (validarCampos($camposRequeridos)) {
        // Obtener el ID del cliente a actualizar desde el formulario
        $id_ct = $_POST['id_ct'];

        // Obtener los datos actualizados del cliente desde el formulario
        $dui_ct = $_POST['dui_ct'];
        $nombre_ct = $_POST['nombre_ct'];
        $apellido_ct = $_POST['apellido_ct'];
        $fecha_nac_ct = $_POST['fecha_nac_ct'];
        $email_ct = $_POST['email_ct'];
        $telefono_ct = $_POST['telefono_ct'];
        $direccion_ct = $_POST['direccion_ct'];
        $estado_ct = $_POST['estado_ct'];

        // Consulta de actualización
        $query = "UPDATE clientes_dsi SET dui_ct = ?, nombre_ct = ?, apellido_ct = ?, fecha_nac_ct = ?, email_ct = ?, telefono_ct = ?, direccion_ct = ?, estado_ct = ? WHERE id_ct = ?";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("ssssssssi", $dui_ct, $nombre_ct, $apellido_ct, $fecha_nac_ct, $email_ct, $telefono_ct, $direccion_ct, $estado_ct, $id_ct);
            // Ejecutar la consulta
            $stmt->execute();
            // Verificar si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                // La actualización fue exitosa, realizar las acciones adicionales necesarias
                // ...
                // Redireccionar al usuario a la página de gestión de clientes
                header("Location: ../Vista/aa_crear_cliente.php");
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

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Último cliente agregado
                        <div style="float: right;">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClienteModal"><i class="fa-solid fa-circle-plus"></i>Agregar Cliente</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>DUI</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                    <th>Funciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM clientes_dsi";
                                $result = $conn->query($query);
                                // Verificar si se encontraron resultados
                                if ($result->num_rows > 0) {
                                    // Iterar sobre los resultados y generar las filas de la tabla
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<td>" . $row['id_ct'] . "</td>";
                                        echo "<td>" . $row['dui_ct'] . "</td>";
                                        echo "<td>" . $row['nombre_ct'] . "</td>";
                                        echo "<td>" . $row['apellido_ct'] . "</td>";
                                        $fecha_nac_ct = date('F d, Y', strtotime($row['fecha_nac_ct']));
                                        echo "<td>" . $fecha_nac_ct . "</td>";
                                        echo "<td>" . $row['email_ct'] . "</td>";
                                        echo "<td>" . $row['telefono_ct'] . "</td>";
                                        echo "<td>" . $row['direccion_ct'] . "</td>";
                                        echo "<td>" . $row['estado_ct'] . "</td>";
                                        echo '<td>
                                                <button class="btn btn-primary" onclick="cargarDatosCliente(' . $row['id_ct'] . ')" data-bs-toggle="modal" data-bs-target="#editClienteModal">Editar <i class="fas fa-pencil-alt" style="color: white;"></i></button>
                                            </td>';
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No se encontraron registros en la tabla clientes_dsi.</td></tr>";
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

        <!------------------------ Modal: Crear Nuevo Cliente ------------------------>
        <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="dui">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="nombre">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="apellido">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="fechaNacimiento" class="col-sm-5 col-form-label">Fecha de Nacimiento</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="fechaNacimiento">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" id="email">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                <div class="col-sm-4">
                                    <input type="tel" class="form-control" id="telefono">
                                </div>
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
        <!------------------------ Modal: Crear Nuevo Cliente ------------------------>

        <!------------------------ Modal: Editar Información de Cliente ------------------------>
        <div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editClienteModal">Editar la información del Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" id="id_ct" name="id_ct">

                            <div class="mb-3 row">
                                <label for="dui" class="col-sm-2 col-form-label">DUI</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="dui" name="dui_ct">
                                </div>
                            </div>

                            <div class=" mb-3 row">
                                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="nombre" name="nombre_ct">
                                </div>
                            </div>

                            <div class=" mb-3 row">
                                <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="apellido" name="apellido_ct">
                                </div>
                            </div>

                            <div class=" mb-3 row">
                                <label for="fechaNacimiento" class="col-sm-5 col-form-label">Fecha de Nacimiento</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="fechaNacimiento" name="fecha_nac_ct">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" id="email" name="email_ct">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                <div class="col-sm-4">
                                    <input type="tel" class="form-control" id="telefono" name="telefono_ct">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion_ct">
                            </div>

                            <div class="mb-3">
                                <label for="estado_ct" class="form-label">Estado</label>
                                <select class="form-select" id="estado_ct" name="estado_ct" required>
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
        <!------------------------ Modal: Editar Información de Cliente ------------------------>

        <!------------------------ Obtener Datos de Cliente ------------------------>
        <script>
            function cargarDatosCliente(id_ct) {
                $.ajax({
                    url: '../Controlador/datos_clientes.php',
                    method: 'POST',
                    data: {
                        id_ct: id_ct
                    },
                    success: function(response) {
                        var cliente = JSON.parse(response);

                        $('#editClienteModal #id_ct').val(cliente.id_ct);
                        $('#editClienteModal #dui').val(cliente.dui_ct);
                        $('#editClienteModal #nombre').val(cliente.nombre_ct);
                        $('#editClienteModal #apellido').val(cliente.apellido_ct);
                        $('#editClienteModal #fechaNacimiento').val(cliente.fecha_nac_ct);
                        $('#editClienteModal #email').val(cliente.email_ct);
                        $('#editClienteModal #telefono').val(cliente.telefono_ct);
                        $('#editClienteModal #direccion').val(cliente.direccion_ct);

                        // Cargar los valores posibles de estado desde la base de datos
                        var estados = ["ACTIVO", "INACTIVO"];
                        var selectEstado = $('#editClienteModal #estado_ct');
                        selectEstado.empty(); // Limpiar opciones existentes

                        for (var i = 0; i < estados.length; i++) {
                            var estado = estados[i];
                            var selected = (estado === cliente.estado_ct) ? 'selected' : '';
                            selectEstado.append('<option value="' + estado + '" ' + selected + '>' + estado + '</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        </script>
        <!------------------------ Obtener Datos de Cliente ------------------------>


</body>

</html>