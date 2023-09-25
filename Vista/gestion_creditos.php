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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('num_credito', 'dui', 'nombre_completo', 'monto', 'tipo_pago', 'fecha_ini', 'fecha_fin', 'plazo', 'interes', 'monto_total', 'monto_pendiente');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del cliente desde el formulario
        $num_credito = $_POST['num_credito'];
        $dui = $_POST['dui'];
        $nombre_completo = $_POST['nombre_completo'];
        $monto = $_POST['monto'];
        $tipo_pago = $_POST['tipo_pago'];
        $fecha_ini = $_POST['fecha_ini'];
        $fecha_fin = $_POST['fecha_fin'];
        $plazo = $_POST['plazo'];
        $interes = $_POST['interes'];
        $monto_total = $_POST['monto_total'];
        $monto_pendiente = $_POST['monto_pendiente'];

        // Consulta de inserción
        $query = "INSERT INTO creditos_dsi (num_credito, dui, nombre_completo, monto, tipo_pago, fecha_ini, fecha_fin, plazo, interes, monto_total, monto_pendiente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("sssssssssss", $num_credito, $dui, $nombre_completo, $monto, $tipo_pago, $fecha_ini, $fecha_fin, $plazo, $interes, $monto_total, $monto_pendiente);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción fue exitosa
            if ($stmt->affected_rows > 0) {
                // La inserción fue exitosa, realizar las acciones adicionales necesarias
                // ...
                echo "Registro insertado exitosamente.";
            } else {
                echo "Error al insertar el registro en la base de datos.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    } else {
        echo "Todos los campos requeridos deben ser llenados.";
    }
}

// Función para generar el número de crédito automático
function generarNumeroCredito($conn)
{
    $sql = "SELECT MAX(id) AS max_id FROM creditos_dsi";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $numeroActual = $row["max_id"];
        if (!is_null($numeroActual)) {
            $nuevoNumeroCredito = "DSI-" . str_pad($numeroActual + 1, 5, "0", STR_PAD_LEFT);
            return $nuevoNumeroCredito;
        } else {
            return "DSI-00001";
        }
    } else {
        return "DSI-00001";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Créditos</title>

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
                    <h1 class="mt-4">Gestión Créditos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">DSI ONE / Gestión Créditos</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Información de Créditos
                            <div style="float: right;">
                                <button class="btn btn-danger" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Exportar a PDF</button>
                                <button class="btn btn-success" onclick="exportToExcel()"><i class="far fa-file-excel"></i> Exportar a Excel</button>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCreditoModal"><i class="fa-solid fa-circle-plus"></i> Crear Crédito</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Número de Crédito</th>
                                        <th>DUI del Cliente</th>
                                        <th>Nombre del Cliente</th>
                                        <th>Monto</th>
                                        <th>Tipo de Pago</th>
                                        <th>Fecha de Inicio</th>
                                        <th>Fecha de Finalización</th>
                                        <th>Plazo</th>
                                        <th>Interes</th>
                                        <th>Monto Total</th>
                                        <th>Monto Pendiente</th>
                                        <th>Ver Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM creditos_dsi";
                                    $result = $conn->query($query);
                                    // Verificar si se encontraron resultados
                                    if ($result->num_rows > 0) {
                                        // Iterar sobre los resultados y generar las filas de la tabla
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            $fecha_ini = date('F d, Y', strtotime($row['fecha_ini']));
                                            $fecha_fin = date('F d, Y', strtotime($row['fecha_fin']));
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['num_credito'] . "</td>";
                                            echo "<td>" . $row['dui'] . "</td>";
                                            echo "<td>" . $row['nombre_completo'] . "</td>";
                                            echo "<td>" . $row['monto'] . "</td>";
                                            echo "<td>" . $row['tipo_pago'] . "</td>";
                                            echo "<td>" . $row['fecha_ini'] . "</td>";
                                            echo "<td>" . $row['fecha_fin'] . "</td>";
                                            echo "<td>" . $row['plazo'] . "</td>";
                                            echo "<td>" . $row['interes'] . "</td>";
                                            echo "<td>" . $row['monto_total'] . "</td>";
                                            echo "<td>" . $row['monto_pendiente'] . "</td>";
                                            echo "<td>
                                                    <a class='btn btn-primary' href='../DSI_ONE/Vista/cuotas.php?id=" . $row['id'] . "'><i class='fas fa-pencil-alt' style='color: white;'></i> Ver Detalles</a>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='13'>No se encontraron registros en la tabla clientes_dsi.</td></tr>";
                                    }
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



    <!-- Agregar Crédito Modal -->
    <div class="modal fade" id="addCreditoModal" tabindex="-1" aria-labelledby="addCreditoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCreditoModalLabel">Agregar Crédito Nuevo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="num_credito" class="form-label">Número de Crédito</label>
                                <input type="text" class="form-control" id="num_credito" name="num_credito" value="<?php echo generarNumeroCredito($conn); ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="dui" class="form-label">Seleccionar DUI</label>
                                <select class="form-select" id="dui" name="dui" required>
                                    <option value="" selected disabled>Seleccionar DUI</option>
                                    <?php
                                    // Incluir el archivo de conexión a la base de datos
                                    require_once "../Controlador/db_connection.php";
                                    // Consulta los datos de la tabla clientes_dsi
                                    $sql = "SELECT dui_ct, nombre_ct, apellido_ct FROM clientes_dsi";
                                    $result = mysqli_query($conn, $sql);
                                    // Verifica si se encontraron registros
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $cliente_dui = $row['dui_ct'];
                                            $cliente_nombre = $row['nombre_ct'] . " " . $row['apellido_ct'];
                                            echo "<option value='$cliente_dui'>$cliente_dui - $cliente_nombre</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>No se encontraron clientes</option>";
                                    }
                                    // Cierra la conexión a la base de datos
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="nombre_completo" class="form-label">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                    <option value="" selected disabled>Seleccionar tipo de Pago</option>
                                    <option value="quincenal">Quincenal</option>
                                    <option value="mensual">Mensual</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="plazo" class="form-label">Plazo</label>
                                <select class="form-select" id="plazo" name="plazo" required>
                                    <option value="" selected disabled>Seleccionar mes de plazo</option>
                                    <option value="3">3 meses</option>
                                    <option value="6">6 meses</option>
                                    <option value="9">9 meses</option>
                                    <option value="12">12 meses</option>
                                    <option value="15">15 meses</option>
                                    <option value="18">18 meses</option>
                                    <option value="21">21 meses</option>
                                    <option value="24">24 meses</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="fecha_ini" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_ini" name="fecha_ini" required>
                            </div>
                            <div class="col-6">
                                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                                <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" onchange="calculateFechaFin()" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="monto" class="form-label">Monto</label>
                                <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                            </div>
                            <div class="col-3">
                                <label for="interes" class="form-label">Interés</label>
                                <select class="form-select" id="interes" name="interes" required>
                                    <option value="0.03">3%</option>
                                    <option value="0.05">5%</option>
                                    <option value="0.07">7%</option>
                                    <option value="0.09">9%</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="monto_total" class="form-label">Monto Total</label>
                                <input type="text" class="form-control" id="monto_total" name="monto_total" readonly>
                            </div>
                            <div class="col-3">
                                <label for="monto_pendiente" class="form-label">Cuota por plazo</label>
                                <input type="text" class="form-control" id="monto_pendiente" name="monto_pendiente" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Crear Crédito</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
        // Obtener el valor del DUI seleccionado y mostrar el nombre completo del cliente
        document.getElementById("dui").addEventListener("change", function() {
            var selectedDui = this.value;
            var options = this.options;
            var selectedOption = options[options.selectedIndex];
            var nombreCompleto = selectedOption.text.split(" - ")[1];
            document.getElementById("nombre_completo").value = nombreCompleto;
        });

        // Calcular la fecha de finalización según el tipo de pago y el plazo seleccionado
        document.getElementById("tipo_pago").addEventListener("change", calculateFechaFin);
        document.getElementById("plazo").addEventListener("change", calculateFechaFin);
        document.getElementById("fecha_ini").addEventListener("change", calculateFechaFin);

        function calculateFechaFin() {
            var tipoPago = document.getElementById("tipo_pago").value;
            var plazo = parseInt(document.getElementById("plazo").value);
            var fechaInicio = new Date(document.getElementById("fecha_ini").value);
            var fechaFin = new Date(fechaInicio);

            if (tipoPago === "quincenal") {
                fechaFin.setMonth(fechaInicio.getMonth() + plazo);
            } else if (tipoPago === "mensual") {
                fechaFin.setMonth(fechaInicio.getMonth() + plazo);
            }

            var dia = fechaFin.getDate();
            var mes = fechaFin.getMonth() + 1; // Los meses en JavaScript son base 0, por lo que se agrega 1
            var anio = fechaFin.getFullYear();

            // Verificar si el valor calculado es NaN
            if (isNaN(dia) || isNaN(mes) || isNaN(anio)) {
                document.getElementById("fecha_fin").value = "";
            } else {
                // Formatear la fecha como "dd/mm/yyyy"
                var fechaFinFormateada = ("00" + dia).slice(-2) + "/" + ("00" + mes).slice(-2) + "/" + anio;
                document.getElementById("fecha_fin").value = fechaFinFormateada;
            }
        }

        // Calcular el monto pendiente según el monto total, el tipo de pago y el plazo seleccionado
        document.getElementById("monto_total").addEventListener("input", recalcularMontoPendiente);
        document.getElementById("tipo_pago").addEventListener("change", recalcularMontoPendiente);
        document.getElementById("plazo").addEventListener("change", recalcularMontoPendiente);

        function recalcularMontoPendiente() {
            var montoTotal = parseFloat(document.getElementById("monto_total").value);
            var tipoPago = document.getElementById("tipo_pago").value;
            var plazo = parseInt(document.getElementById("plazo").value);
            var factorPago = tipoPago === "quincenal" ? 2 : 1;
            var montoPendiente = montoTotal / (factorPago * plazo);

            if (isNaN(montoPendiente)) {
                document.getElementById("monto_pendiente").value = "";
            } else {
                document.getElementById("monto_pendiente").value = montoPendiente.toFixed(2);
            }
        }

        // Calcular el monto total según el monto y el interés seleccionado
        document.getElementById("monto").addEventListener("input", recalcularMontoTotal);
        document.getElementById("interes").addEventListener("change", recalcularMontoTotal);

        function recalcularMontoTotal() {
            var monto = parseFloat(document.getElementById("monto").value);
            var interes = parseFloat(document.getElementById("interes").value);
            var montoTotal = monto * interes || 0; // Asegurarse de que el resultado sea un número válido
            var montoFinal = monto + montoTotal;

            if (isNaN(montoFinal)) {
                document.getElementById("monto_total").value = "";
            } else {
                document.getElementById("monto_total").value = montoFinal.toFixed(2);
            }

            // Llamar a la función recalcularMontoPendiente para actualizar el monto pendiente
            recalcularMontoPendiente();
        }


        // Calcular la fecha de finalización y los montos al cargar la página
        calculateFechaFin();
        recalcularMontoPendiente();
        recalcularMontoTotal();
    </script>

    <script>
        function exportToExcel() {
            // Crear un nuevo libro de Excel
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Créditos');

            // Agregar los títulos de las columnas
            worksheet.columns = [{
                    header: 'ID',
                    key: 'id'
                },
                {
                    header: 'N° Crédito',
                    key: 'num_credito'
                },
                {
                    header: 'DUI',
                    key: 'dui'
                },
                {
                    header: 'Nombre Completo',
                    key: 'nombre_completo'
                },
                {
                    header: 'Monto',
                    key: 'monto'
                },
                {
                    header: 'Tipo Pago',
                    key: 'tipo_pago'
                },
                {
                    header: 'Fecha Inicio',
                    key: 'fecha_ini'
                },
                {
                    header: 'Fecha Fin',
                    key: 'fecha_fin'
                },
                {
                    header: 'Plazo',
                    key: 'plazo'
                },
                {
                    header: 'Interes',
                    key: 'interes'
                },
                {
                    header: 'Monto Total',
                    key: 'monto_total'
                },
                {
                    header: 'Cuota',
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
                saveAs(blob, 'Creditos_Clientes.xlsx');
            });
        }
    </script>

    <!-- Script para exportar la tabla a un PDF -->
    <script>
        function exportToPDF() {
            // Redireccionar a la página que genera el PDF
            window.location.href = '../Modelo/pdf_creditos.php';
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