<?php
include "../Controlador/db_connection.php";
session_start();

// Verificar si la sesión no está activa
if (!isset($_SESSION['username'])) {
    // Redireccionar al usuario a la página de inicio de sesión
    header("Location: ../index.php");
    exit();
}

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
    $camposRequeridos = array('nombre', 'apellido', 'dui', 'fecha_nac', 'telefono', 'email', 'direccion');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del cliente desde el formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dui = $_POST['dui'];
        $fecha_nac = $_POST['fecha_nac'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];

        // Consulta de inserción
        $query = "INSERT INTO clientes_dsi (nombre, apellido, dui, fecha_nac, telefono, email, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("sssssss", $nombre, $apellido, $dui, $fecha_nac, $telefono, $email, $direccion);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción fue exitosa
            if ($stmt->affected_rows > 0) {
                // La inserción fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de clientes
                header("Location: ../Vista/gestion_clientes.php");
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
    $camposRequeridos = array('id','nombre', 'apellido', 'dui', 'fecha_nac', 'telefono', 'email', 'direccion');
    if (validarCampos($camposRequeridos)) {
        // Obtener el ID del cliente a actualizar desde el formulario
        $id = $_POST['id'];

        // Obtener los datos actualizados del cliente desde el formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dui = $_POST['dui'];
        $fecha_nac = $_POST['fecha_nac'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];

        // Consulta de actualización
        $query = "UPDATE clientes_dsi SET nombre = ?, apellido = ?, dui = ?, fecha_nac = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("sssssssi", $nombre, $apellido, $dui, $fecha_nac, $telefono, $email, $direccion, $id);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                // La actualización fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al usuario a la página de gestión de clientes
                header("Location: ../Vista/gestion_clientes.php");
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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inicio</title>

    <!-- Incluir Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Incluir DataTables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Incluir DataTables JS -->
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Incluir TableExport JS -->
    <script src="https://cdn.jsdelivr.net/npm/tableexport@5.2.0/dist/js/tableexport.min.js"></script>

    <!-- Incluir js-xlsx JS -->
    <script src="https://unpkg.com/xlsx@0.16.8/dist/xlsx.full.min.js"></script>

    <!-- Incluir Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <!-- Incluir FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../Complementos/CSS/style.css">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../Vista/home.php">DSI ONE SA de CV</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="../Vista/home.php"><i class="fas fa-bars"></i></button>

        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">

            </div>
        </form>

        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <a class="btn btn-link nav-link" id="btnLogout" href="../Controlador/logout.php">Cerrar Sesión <i class="fas fa-sign-out-alt"></i></a>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">General</div>
                        <a class="nav-link" href="../Vista/home.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home-lg"></i></div>
                            Inicio
                        </a>

                        <div class="sb-sidenav-menu-heading">Gestiones</div>
                        <a class="nav-link" href="../Vista/gestion_clientes.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                            Gestionar Clientes
                        </a>

                        <a class="nav-link" href="../Vista/gestion_creditos.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                            Gestionar Créditos
                        </a>

                        <a class="nav-link" href="../Vista/gestion_pagos.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-hand-holding-usd"></i></div>
                            Gestionar Pagos
                        </a>

                        <div class="sb-sidenav-menu-heading">Reportes</div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Sesión iniciada como:</div>
                    <?php echo $_SESSION['username']; ?>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Gestión Clientes</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">DSI ONE / Gestión Clientes</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Información de los Clientes
                            <div style="float: right;">
                                <button class="btn btn-danger" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Exportar a PDF</button>
                                <button class="btn btn-success" onclick="exportToExcel()"><i class="far fa-file-excel"></i> Exportar a Excel</button>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClienteModal"><i class="fa-solid fa-circle-plus"></i>Agregar Cliente</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>DUI</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Dirección</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Incluir el archivo de conexión a la base de datos
                                    //include "../Controlador/db_connection.php";
                                    // Realizar la consulta para obtener los datos de la tabla clientes_dsi
                                    $query = "SELECT * FROM clientes_dsi";
                                    $result = $conn->query($query);
                                    // Verificar si se encontraron resultados
                                    if ($result->num_rows > 0) {
                                        // Iterar sobre los resultados y generar las filas de la tabla
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['nombre'] . "</td>";
                                            echo "<td>" . $row['apellido'] . "</td>";
                                            echo "<td>" . $row['dui'] . "</td>";
                                            $fecha_nac = date('F d, Y', strtotime($row['fecha_nac']));
                                            echo "<td>" . $fecha_nac . "</td>";
                                            echo "<td>" . $row['telefono'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>" . $row['direccion'] . "</td>";
                                            echo '<td>
                                                    <button class="btn btn-primary" onclick="cargarDatosCliente(' . $row['id'] . ')" data-bs-toggle="modal" data-bs-target="#editClienteModal"><i class="fas fa-pencil-alt" style="color: white;"></i></button>
                                                    <button class="btn btn-danger" onclick="eliminarCliente(' . $row['id'] . ')"><i class="fas fa-trash-alt" style="color: white;"></i></button>
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
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; DSI ONE SA de CV 2023</div>
                        <div>
                            <a href="#">Política de privacidad</a>
                            &middot;
                            <a href="#">Términos &amp; condiciones</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Agregar Cliente Modal -->
    <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClienteModalLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="dui" class="form-label">DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
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

    <!-- Editar Cliente Modal -->
    <div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClienteModalLabel">Editar la información del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="dui" class="form-label">DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
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



    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                }
            });
        });
    </script>

    <script>
        function cargarDatosCliente(id) {
            $.ajax({
                url: '../Modelo/obtener_datos_cliente.php',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    // Parsear la respuesta JSON
                    var cliente = JSON.parse(response);

                    // Llenar los campos del formulario en el modal con los datos del cliente
                    $('#editClienteModal #id').val(cliente.id);
                    $('#editClienteModal #nombre').val(cliente.nombre);
                    $('#editClienteModal #apellido').val(cliente.apellido);
                    $('#editClienteModal #dui').val(cliente.dui);
                    $('#editClienteModal #fecha_nac').val(cliente.fecha_nac);
                    $('#editClienteModal #telefono').val(cliente.telefono);
                    $('#editClienteModal #email').val(cliente.email);
                    $('#editClienteModal #direccion').val(cliente.direccion);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    </script>

    <script>
        function eliminarCliente(clienteId) {
            if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
                // Enviar una solicitud AJAX al servidor para eliminar el cliente
                $.ajax({
                    url: "../Modelo/eliminar_cliente.php",
                    type: "POST",
                    data: {
                        cliente_id: clienteId
                    },
                    success: function(response) {
                        // Redireccionar a la página de gestión de clientes después de la eliminación
                        window.location.href = "../Vista/gestion_clientes.php";
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }
    </script>

    <script>
        function exportToExcel() {
            // Crear un nuevo libro de Excel
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Clientes');

            // Agregar los títulos de las columnas
            worksheet.columns = [
                { header: 'ID', key: 'id' },
                { header: 'Nombre', key: 'nombre' },
                { header: 'Apellido', key: 'apellido' },
                { header: 'DUI', key: 'dui' },
                { header: 'Fecha de Nacimiento', key: 'fecha_nac' },
                { header: 'Teléfono', key: 'telefono' },
                { header: 'Email', key: 'email' },
                { header: 'Dirección', key: 'direccion' }
            ];

            // Obtener los datos de la tabla
            var table = document.getElementById('datatablesSimple');
            var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            // Agregar los datos a las filas del libro de Excel
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var rowData = [];

                for (var j = 0; j < row.cells.length - 1; j++) {
                    rowData.push(row.cells[j].textContent);
                }

                worksheet.addRow(rowData);
            }

            // Guardar el archivo Excel
            workbook.xlsx.writeBuffer().then(function (data) {
                var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                saveAs(blob, 'clientes.xlsx');
            });
        }
    </script>

    <!-- Script para exportar la tabla a un PDF -->
    <script>
        function exportToPDF() {
            // Redireccionar a la página que genera el PDF
            window.location.href = '../Modelo/pdf_cliente.php';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/datatables.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluye estas líneas para exportar en excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script> 

    
    <script src="../Complementos/JS/script.js"></script>
    




</body>

</html>