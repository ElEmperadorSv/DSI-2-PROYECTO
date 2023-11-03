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

// Verificar si se ha enviado el formulario de agregar crédito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitAgregarCredito'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('dui_ct', 'nombre_completo_ct', 'num_credito', 'producto', 'cantidad_producto', 'tipo_pago', 'plazo', 'fecha_ini', 'fecha_fin', 'monto', 'interes', 'monto_total', 'cuota', 'monto_pendiente');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del nuevo crédito desde el formulario
        $duiCliente = $_POST['dui_ct'];
        $cliente = $_POST['nombre_completo_ct'];
        $numCredito = $_POST['num_credito'];
        $producto = $_POST['producto'];
        $cantidadProducto = $_POST['cantidad_producto'];
        $tipoPago = $_POST['tipo_pago'];
        $plazo = $_POST['plazo'];
        $fechaInicio = $_POST['fecha_ini'];
        $fechaFin = $_POST['fecha_fin'];
        $monto = $_POST['monto'];
        $interes = $_POST['interes'];
        $montoTotal = $_POST['monto_total'];
        $cuota = $_POST['cuota'];
        $montoPendiente = $_POST['monto_pendiente'];

        // Consulta para verificar el stock del producto
        $consultaStock = "SELECT stock_pd FROM productos_dsi WHERE nombre_pd = ?";
        $stmtStock = $conn->prepare($consultaStock);
        $stmtStock->bind_param("s", $producto);
        $stmtStock->execute();
        $stmtStock->bind_result($stockProducto);
        $stmtStock->fetch();
        $stmtStock->close();

        // Verificar si hay suficiente stock
        if ($stockProducto >= $cantidadProducto) {
            // Restar la cantidad correspondiente al stock del producto
            $nuevoStock = $stockProducto - $cantidadProducto;

            // Actualizar el stock del producto
            $actualizarStock = "UPDATE productos_dsi SET stock_pd = ? WHERE nombre_pd = ?";
            $stmtActualizarStock = $conn->prepare($actualizarStock);
            $stmtActualizarStock->bind_param("is", $nuevoStock, $producto);
            $stmtActualizarStock->execute();
            $stmtActualizarStock->close();

            // Verificar si el stock llega a cero y cambiar el estado a INACTIVO
            if ($nuevoStock == 0) {
                $actualizarEstado = "UPDATE productos_dsi SET estado_pd = 'INACTIVO' WHERE nombre_pd = ?";
                $stmtActualizarEstado = $conn->prepare($actualizarEstado);
                $stmtActualizarEstado->bind_param("s", $producto);
                $stmtActualizarEstado->execute();
                $stmtActualizarEstado->close();
            }

            // Consulta de inserción para créditos
            $query = "INSERT INTO creditos_dsi (dui_ct, cliente, num_credito, producto, cantidad_producto, tipo_pago, plazo, fecha_ini, fecha_fin, monto, interes, monto_total, cuota, monto_pendiente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Preparar la consulta
            $stmt = $conn->prepare($query);

            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt) {
                // Vincular los parámetros
                $stmt->bind_param("ssssssssssssss", $duiCliente, $cliente, $numCredito, $producto, $cantidadProducto, $tipoPago, $plazo, $fechaInicio, $fechaFin, $monto, $interes, $montoTotal, $cuota, $montoPendiente);

                // Ejecutar la consulta
                $stmt->execute();

                // Verificar si la inserción fue exitosa
                if ($stmt->affected_rows > 0) {
                    // La inserción fue exitosa, realizar las acciones adicionales necesarias
                    echo "<script>alert('Nuevo registro realizado con exito.');</script>";

                    // Redireccionar a la página de gestión de créditos o a donde desees
                    header("Location: ../Vista/ba_crear_credito.php");
                    exit();
                } else {
                    // Ocurrió un error al insertar el nuevo registro
                    echo "<script>alert('Error al insertar el nuevo registro en la base de datos.');</script>";
                }

                // Cerrar la declaración
                $stmt->close();
            } else {
                // Ocurrió un error al preparar la consulta
                echo "<script>alert('Error al preparar la consulta.');</script>";
            }
        } else {
            // No hay suficiente stock del producto
            echo "<script>alert('No hay suficiente stock del producto.');</script>";
        }
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

<head>
    <title>Crear Crédito</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Crear un nuevo Crédito</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> DASHBOARD </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Información de Créditos
                        <div style="float: right;">
                            <button class="btn btn-danger" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i></button>
                            <button class="btn btn-success" onclick="exportToExcel()"><i class="far fa-file-excel"></i></button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCreditoModal"><i class="fa-solid fa-circle-plus"></i> Crear Crédito</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive" style="overflow-x: auto;">
                        <table id="datatablesSimple" class="display" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>DUI del Cliente</th>
                                    <th>Nombre del Cliente</th>
                                    <th>Número de Crédito</th>
                                    <th>Producto</th>
                                    <th>Cantidad de Productos</th>
                                    <th>Monto</th>
                                    <th>Interés</th>
                                    <th>Plazo</th>
                                    <th>Monto Total</th>
                                    <th>Cuota</th>
                                    <th>Monto Pendiente</th>
                                    <th>Tipo de Pago</th>
                                    <th>Fecha de Inicio</th>
                                    <th>Fecha de Finalización</th>
                                    <th>Estado</th>
                                    <th>Función</th>
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
                                        echo "<td>" . $row['dui_ct'] . "</td>";
                                        echo "<td>" . $row['cliente'] . "</td>";
                                        echo "<td>" . $row['num_credito'] . "</td>";
                                        echo "<td>" . $row['producto'] . "</td>";
                                        echo "<td>" . $row['cantidad_producto'] . "</td>";
                                        echo "<td>" . $row['monto'] . "</td>";
                                        echo "<td>" . $row['interes'] . "</td>";
                                        echo "<td>" . $row['plazo'] . "</td>";
                                        echo "<td>" . $row['monto_total'] . "</td>";
                                        echo "<td>" . $row['cuota'] . "</td>";
                                        echo "<td>" . $row['monto_pendiente'] . "</td>";
                                        echo "<td>" . $row['tipo_pago'] . "</td>";
                                        echo "<td>" . $row['fecha_ini'] . "</td>";
                                        echo "<td>" . $row['fecha_fin'] . "</td>";
                                        echo "<td>" . $row['estado_credito'] . "</td>";
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

                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>

        <?php include '../Modelo/o_scrips_generales.php'; ?>

        <!------------------------------------ Agregar Crédito Modal ------------------------------------>
        <div class="modal fade" id="addCreditoModal" tabindex="-1" aria-labelledby="addCreditoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
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


                            <!-- Información del Cliente -->
                            <h6 style="color: blue;">Información del Cliente</h6>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="dui_ct" class="form-label">Seleccionar DUI</label>
                                    <select class="form-select" id="dui_ct" name="dui_ct" required>
                                        <option value="" selected disabled>Seleccionar DUI</option>
                                        <?php
                                        // Incluir el archivo de conexión a la base de datos
                                        require_once "../Controlador/db_connection.php";

                                        // Consulta los datos de la tabla clientes_dsi
                                        $sql = "SELECT dui_ct, nombre_completo_ct FROM clientes_dsi";
                                        $result = mysqli_query($conn, $sql);

                                        // Verifica si se encontraron registros
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $cliente_dui = $row['dui_ct'];
                                                $cliente_nombre = $row['nombre_completo_ct'];
                                                echo "<option value='$cliente_dui'>$cliente_dui - $cliente_nombre</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled>No se encontraron clientes</option>";
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="nombre_completo_ct" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombre_completo_ct" name="nombre_completo_ct" readonly>
                                </div>
                            </div>


                            <!-- Información del Producto -->
                            <h6 style="color: blue;">Información del Producto</h6>
                            <hr>
                            <?php
                            // Consulta SQL para obtener productos
                            $query = "SELECT id_pd, nombre_pd FROM productos_dsi";
                            $result = $conn->query($query);
                            ?>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="producto" class="form-label">Producto</label>
                                    <select class="form-select form-control" id="producto" name="producto" required>
                                        <option value="" selected disabled>Seleccionar producto</option>
                                        <?php
                                        // Generar las opciones dinámicamente
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["nombre_pd"] . '">' . $row["nombre_pd"] . '</option>';
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label for="cantidad_producto" class="form-label">Cantidad</label>
                                    <input type="number" step="1.0" class="form-control" id="cantidad_producto" name="cantidad_producto">
                                </div>
                            </div>


                            <!-- Información del Crédito -->
                            <h6 style="color: blue;">Información del Crédito</h6>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                    <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                        <option value="" selected disabled>Tipo de Pago</option>
                                        <option value="quincenal">Quincenal</option>
                                        <option value="mensual">Mensual</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="plazo" class="form-label">Plazo</label>
                                    <select class="form-select" id="plazo" name="plazo" required>
                                        <option value="" selected disabled>Tipo de Plazo</option>
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
                                <div class="col-3">
                                    <label for="fecha_ini" class="form-label">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="fecha_ini" name="fecha_ini" required>
                                </div>
                                <div class="col-3">
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
                                    <label for="cuota" class="form-label">Cuota por plazo</label>
                                    <input type="text" class="form-control" id="cuota" name="cuota" readonly>
                                </div>
                                <div class="col-3">
                                    <input type="hidden" class="form-control" id="monto_pendiente" name="monto_pendiente" readonly>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="submitAgregarCredito">Crear Crédito</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!------------------------------------ Agregar Crédito Modal ------------------------------------>

    <!-------------- Script para el scroll horizontal en card de datatable -------------->
    <script>
        $(document).ready(function() {
            var dataTableConfig = {
                scrollX: true,
                autoWidth: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                }
            };

            if ($.fn.DataTable.isDataTable('#datatablesSimple')) {
                $('#datatablesSimple').DataTable().destroy();
            }

            $('#datatablesSimple').DataTable(dataTableConfig);
        });
    </script>
    <!-------------- Script para el scroll horizontal en card de datatable -------------->


    <script>
        // Función para obtener el valor del DUI seleccionado y mostrar el nombre completo del cliente
        function actualizarNombreCompleto() {
            var selectedDui = document.getElementById("dui_ct").value;
            var options = document.getElementById("dui_ct").options;
            var selectedOption = options[options.selectedIndex];
            var nombreCompleto = selectedOption.text.split(" - ")[1];

            // Actualizar el campo "Nombre del Cliente"
            document.getElementById("nombre_completo_ct").value = nombreCompleto;
        }

        // Agregar un evento al cargar la página para inicializar el comportamiento
        window.addEventListener("load", function() {
            // Llamar a la función para inicializar el nombre del cliente
            actualizarNombreCompleto();

            // Agregar un evento al cambio en el select
            document.getElementById("dui_ct").addEventListener("input", function() {
                var inputDui = this.value;
                var formattedDui = inputDui.replace(/\D/g, "").substring(0, 9); // Obtener solo los primeros 9 dígitos

                this.value = formattedDui;

                // Llamar a la función para actualizar el nombre completo
                actualizarNombreCompleto();
            });
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

        // Calcular cuota según el monto total, el tipo de pago y el plazo seleccionado
        document.getElementById("monto_total").addEventListener("input", recalcularMontoPendiente);
        document.getElementById("tipo_pago").addEventListener("change", recalcularMontoPendiente);
        document.getElementById("plazo").addEventListener("change", recalcularMontoPendiente);

        function recalcularMontoPendiente() {
            var montoTotal = parseFloat(document.getElementById("monto_total").value);
            var tipoPago = document.getElementById("tipo_pago").value;
            var plazo = parseInt(document.getElementById("plazo").value);
            var factorPago = tipoPago === "quincenal" ? 2 : 1;
            var cuota = montoTotal / (factorPago * plazo);

            if (isNaN(cuota)) {
                document.getElementById("cuota").value = "";
            } else {
                document.getElementById("cuota").value = cuota.toFixed(2);
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

        // Calcular el monto pendiente según el monto y el interés seleccionado
        document.getElementById("monto").addEventListener("input", recalcularMontoTotal2);
        document.getElementById("interes").addEventListener("change", recalcularMontoTotal2);

        function recalcularMontoTotal2() {
            var monto = parseFloat(document.getElementById("monto").value);
            var interes = parseFloat(document.getElementById("interes").value);
            var montoTotal = monto * interes || 0; // Asegurarse de que el resultado sea un número válido
            var montoFinal = monto + montoTotal;

            if (isNaN(montoFinal)) {
                document.getElementById("monto_pendiente").value = "";
            } else {
                document.getElementById("monto_pendiente").value = montoFinal.toFixed(2);
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
                    header: 'DUI',
                    key: 'dui_ct'
                },
                {
                    header: 'Nombre Completo',
                    key: 'cliente'
                },
                {
                    header: 'N° Crédito',
                    key: 'num_credito'
                },
                {
                    header: 'Producto',
                    key: 'producto'
                },
                {
                    header: 'Cantidad Producto',
                    key: 'cantidad_producto'
                },
                {
                    header: 'Monto',
                    key: 'monto'
                },
                {
                    header: 'Interes',
                    key: 'interes'
                },
                {
                    header: 'Plazo',
                    key: 'plazo'
                },
                {
                    header: 'Monto Total',
                    key: 'monto_total'
                },
                {
                    header: 'Cuota',
                    key: 'cuota'
                },
                {
                    header: 'Monto Pendiente',
                    key: 'monto_pendiente'
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
                    header: 'Estado',
                    key: 'estado_credito'
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



</body>

</html>