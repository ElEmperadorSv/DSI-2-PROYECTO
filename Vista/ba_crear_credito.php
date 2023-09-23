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
            $stmt->bind_param("ssssssssssssss", $num_credito, $dui, $nombre_completo, $monto, $tipo_pago, $fecha_ini, $fecha_fin, $plazo, $interes, $monto_total, $monto_pendiente, $producto, $cantidad_producto, $estado_credito);

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

        <!-------------- Script para el scroll horizontal en card de datatable -------------->
        <script>
            $(document).ready(function() {
                if ($.fn.DataTable.isDataTable('#datatablesSimple')) {
                    $('#datatablesSimple').DataTable().destroy();
                }

                $('#datatablesSimple').DataTable({
                    "scrollX": true,
                    "autoWidth": true
                });
            });
        </script>
        <!-------------- Script para el scroll horizontal en card de datatable -------------->


        <!------------------------------ Agregar Crédito Modal ------------------------------>
        <div class="modal fade" id="addCreditoModal" tabindex="-1" aria-labelledby="addCreditoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCreditoModalLabel">Agregar Crédito Nuevo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <!-- ... Otros campos ... -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="num_credito" class="form-label">Número de Crédito</label>
                                    <input type="text" class="form-control" id="num_credito" name="num_credito" value="<?php echo $num_credito; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="dui" class="form-label">DUI del Cliente</label>
                                    <input type="text" class="form-control" id="dui" name="dui" value="<?php echo $dui_ct; ?>" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="cliente" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="cliente" name="cliente" value="<?php echo $cliente; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="producto" class="form-label">Producto</label>
                                    <input type="text" class="form-control" id="producto" name="producto" value="<?php echo $producto; ?>" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="cantidad_producto" class="form-label">Cantidad de Productos</label>
                                    <input type="text" class="form-control" id="cantidad_producto" name="cantidad_producto" value="<?php echo $cantidad_producto; ?>" readonly>
                                </div>
                            </div>
                            <!-- ... Otros campos ... -->
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
                    fechaFin.setDate(fechaInicio.getDate() + (plazo * 15));
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
                    // Formatear la fecha como "yyyy-mm-dd"
                    var fechaFinFormateada = anio + "-" + ("00" + mes).slice(-2) + "-" + ("00" + dia).slice(-2);
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
                var montoTotal = monto + (monto * interes); // Calcular monto total con interés
                var montoFinal = montoTotal;

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



</body>

</html>