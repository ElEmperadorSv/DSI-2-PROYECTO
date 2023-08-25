<?php
include "../Controlador/db_connection.php";
session_start();

// Verificar si la sesión no está activa
if (!isset($_SESSION['username'])) {
    // Redireccionar al usuario a la página de inicio de sesión
    header("Location: ../index.php");
    exit();
}

// Obtener el número de crédito ingresado en el formulario
if (isset($_POST['buscar'])) {
    $numCredito = $_POST['num_credito'];

    // Consultar la base de datos para obtener los datos del crédito y el cliente asociado
    include('../Controlador/db_connection.php'); // Reemplaza con el nombre del archivo de conexión a la base de datos

    $query = "SELECT c.nombre, c.apellido, c.dui, cr.monto_pendiente FROM clientes_dsi c INNER JOIN creditos_dsi cr ON c.dui = cr.dui WHERE cr.num_credito = '$numCredito'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Rellenar los campos del formulario con los datos obtenidos
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $dui = $row['dui'];
        $montoPendiente = $row['monto_pendiente'];
    } else {
        // Crédito no encontrado
        echo "No se encontró ningún crédito con el número ingresado.";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
}

// Guardar el pago en la tabla "pagos_dsi"
if (isset($_POST['submitGuardar'])) {
    // Obtener los datos del formulario
    $numCredito = $_POST['num_credito'];
    $dui = $_POST['dui'];
    $nombre = $_POST['nombre'];
    $fechaPago = $_POST['fecha_pago'];
    $montoPago = $_POST['monto_pago'];
    $montoPendiente = $_POST['monto_pendiente'];

    // Guardar los datos en la tabla "pagos_dsi"
    include('../Controlador/db_connection.php'); // Reemplaza con el nombre del archivo de conexión a la base de datos

    $query = "INSERT INTO pagos_dsi (num_credito, dui, nombre_completo, fecha_pago, monto_pago, monto_pendiente) VALUES ('$numCredito', '$dui', '$nombre', '$fechaPago', '$montoPago', '$montoPendiente')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "El pago se ha realizado exitosamente.";

        // Limpiar los datos buscados después de guardar el pago exitosamente
        $numCredito = '';
        $nombre = '';
        $apellido = '';
        $dui = '';
        $montoPendiente = '';
    } else {
        echo "Error al realizar el pago.";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
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
                    <h1 class="mt-4">Gestión De Pagos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">DSI ONE / Gestión Pagos</li>
                    </ol>

                    <div class="card mb-4">

                        <div class="card-header">
                            <!-- Buscar # de créditos -->
                            <h5 class="modal-title" id="addClienteModalLabel">Buscar Número De Crédito</h5>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <input type="text" name="num_credito" class="form-control" id="num_credito" required>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" name="buscar" class="btn btn-primary" value="Buscar">
                                </div>
                            </form>

                            <?php if (isset($numCredito)) : ?>
                                <h4>Datos del Crédito:</h4>
                                <p>Nombre: <?php echo $nombre; ?></p>
                                <p>Apellido: <?php echo $apellido; ?></p>
                                <p>DUI: <?php echo $dui; ?></p>
                                <p>Monto Pendiente: $ <?php echo $montoPendiente; ?></p>

                                <i class="fas fa-table me-1"></i>
                                Información de los pagos
                                <div style="float: right;">
                                    <button class="btn btn-success" onclick="exportToExcel()"><i class="far fa-file-excel"></i> Exportar a Excel</button>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClienteModal"><i class="fa-solid fa-circle-plus"></i>Agregar Pago</button>
                                </div>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th># Crédito</th>
                                        <th>DUI</th>
                                        <th>Nombre Completo</th>
                                        <th>Fecha de Pago</th>
                                        <th>Monto Pago</th>
                                        <th>Pago Realizado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Incluir el archivo de conexión a la base de datos
                                    include "../Controlador/db_connection.php";
                                    // Realizar la consulta para obtener los datos de la tabla pagos_dsi
                                    $query = "SELECT * FROM pagos_dsi";
                                    $result = $conn->query($query);
                                    // Verificar si se encontraron resultados
                                    if ($result->num_rows > 0) {
                                        // Iterar sobre los resultados y generar las filas de la tabla
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['num_credito'] . "</td>";
                                            echo "<td>" . $row['dui'] . "</td>";
                                            echo "<td>" . $row['nombre_completo'] . "</td>";
                                            $fecha_pago = date('F d, Y', strtotime($row['fecha_pago']));
                                            echo "<td>" . $fecha_pago . "</td>";
                                            echo "<td>" . $row['monto_pago'] . "</td>";
                                            echo "<td>" . $row['monto_pendiente'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No se encontraron registros en la tabla pagos_dsi.</td></tr>";
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

    <!-- Agregar Pago Modal -->
    <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="addClienteModalLabel">Realizar Pago</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <input type="hidden" name="num_credito" value="<?php echo $numCredito; ?>">
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="dui" value="<?php echo $dui; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de Pago:</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="monto_pago" class="form-label">Monto del Pago:</label>
                            <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" required>
                        </div>
                        <input type="hidden" name="monto_pendiente" value="<?php echo $montoPendiente; ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" name="submitGuardar">Realizar Pago</button>
                        </div>
                    </form>
                <?php endif; ?>
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
        function exportToExcel() {
            // Crear un nuevo libro de Excel
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Pagos Clientes');
            // Agregar los títulos de las columnas
            worksheet.columns = [{
                    header: 'ID',
                    key: 'id'
                },
                {
                    header: '# Crédito',
                    key: 'num_credito'
                },
                {
                    header: 'DUI',
                    key: 'dui'
                },
                {
                    header: 'Nombre',
                    key: 'nombre_completo'
                },
                {
                    header: 'Fecha de Pago',
                    key: 'fecha_pago'
                },
                {
                    header: 'Monto Pago',
                    key: 'monto_pago'
                },
                {
                    header: 'Pago Realizado',
                    key: 'monto_pendiente'
                }
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
            workbook.xlsx.writeBuffer().then(function(data) {
                var blob = new Blob([data], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                saveAs(blob, 'pagos_clientes.xlsx');
            });
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/datatables.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluye estas líneas para exportar en excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.3/jspdf.umd.min.js"></script>

    <script src="../Complementos/JS/script.js"></script>


</body>

</html>