<?php
include "../Controlador/db_connection.php";
session_start();

$cliente = "";
$dui_ct = "";
$numCreditos = "";
$montoPendiente = "";
$cuota = "";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['buscar'])) {
    $numCredito = $_POST['num_credito'];

    include('../Controlador/db_connection.php');

    $query = "SELECT c.dui_ct, c.cliente, c.num_credito, c.monto_pendiente, c.cuota FROM creditos_dsi c WHERE c.num_credito = '$numCredito'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $dui_ct = $row['dui_ct'];
        $cliente = $row['cliente'];
        $numCreditos = $row['num_credito'];
        $montoPendiente = $row['monto_pendiente'];
        $cuota = $row['cuota'];
    } else {
        echo "No se encontró ningún crédito con el número ingresado.";
    }

    mysqli_close($conn);
}

if (isset($_POST['submitGuardar'])) {
    $numCredito = $_POST['num_credito'];
    $dui = $_POST['dui_cliente'];
    $nombreCliente = $_POST['nombre_cliente'];
    $fechaPago = $_POST['fecha_pago'];
    $montoPago = $_POST['monto_pago'];
    $estadoPago = $_POST['estado_pago'];

    include('../Controlador/db_connection.php');

    $query = "INSERT INTO pagos_dsi (num_credito, dui_cliente, nombre_cliente, fecha_pago, monto_pago, estado_pago) VALUES (?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssssss", $numCredito, $dui, $nombreCliente, $fechaPago, $montoPago, $estadoPago);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('El pago se ha realizado exitosamente.');</script>";

            // Obtener el nuevo monto pendiente del formulario
            $nuevoMontoPendiente = $_POST['nuevo_monto_pendiente'];

            // Verificar si el monto pendiente es menor o igual a cero
            if ($nuevoMontoPendiente <= 0.00) {
                $nuevoMontoPendiente = 0;
                
                // Actualizar el estado del crédito a "FINALIZADO"
                $updateQuery = "UPDATE creditos_dsi SET monto_pendiente = '$nuevoMontoPendiente', estado_pago = 'FINALIZADO' WHERE num_credito = '$numCredito'";
            } else {
                $updateQuery = "UPDATE creditos_dsi SET monto_pendiente = '$nuevoMontoPendiente' WHERE num_credito = '$numCredito'";
            }

            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                echo "Monto pendiente actualizado correctamente.";
            } else {
                echo "Error al actualizar el monto pendiente del crédito.";
            }
        } else {
            echo "Error al realizar el pago.";
        }
    } else {
        echo "<script>alert('Error al preparar la consulta.');</script>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión de Pagos</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Gestión de Pagos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> Dashboard </a></li>
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
                                <p>Cliente: <?php echo $cliente; ?></p>
                                <p>Dui: <?php echo $dui_ct; ?></p>
                                <p>Número Crédito: <?php echo $numCreditos; ?></p>
                                <p>Monto Pendiente: $ <?php echo $montoPendiente; ?></p>
                                <p>Cuota: $ <?php echo $cuota; ?></p>
                            <?php endif; ?>

                                <i class="fas fa-table me-1"></i>
                                Información de los pagos
                                <div style="float: right;">
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
                                        <th>Estado</th>
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
                                            echo "<td>" . $row['id_pago'] . "</td>";
                                            echo "<td>" . $row['num_credito'] . "</td>";
                                            echo "<td>" . $row['dui_cliente'] . "</td>";
                                            echo "<td>" . $row['nombre_cliente'] . "</td>";
                                            $fecha_pago = date('F d, Y', strtotime($row['fecha_pago']));
                                            echo "<td>" . $fecha_pago . "</td>";
                                            echo "<td>" . $row['monto_pago'] . "</td>";
                                            echo "<td>" . $row['estado_pago'] . "</td>";
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

            <!-- Agregar Pago Modal -->
            <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5 class="modal-title" id="addClienteModalLabel">Realizar Pago</h5>
                            <form method="POST" action="" onsubmit="return calcularNuevoMontoPendiente()">
                                <div class="mb-3">
                                    <input type="hidden" name="num_credito" value="<?php echo $numCreditos; ?>">
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="nombre_cliente" value="<?php echo $cliente; ?>">
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="dui_cliente" value="<?php echo $dui_ct; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha de Pago:</label>
                                    <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="monto_pago" class="form-label">Cuota:</label>
                                    <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" value="<?php echo $cuota; ?>" readOnly required>
                                </div>
                                <div class="mb-3">
                                    <label for="estado_pago" class="form-label">Estado del Pago:</label>
                                    <select name="estado_pago" id="estado_pago" class="form-select" required>
                                        <option value="REALIZADO">REALIZADO</option>
                                        <option value="PENDIENTE">PENDIENTE</option>
                                        <option value="EN MORA">EN MORA</option>
                                    </select>
                                </div>
                                <input type="hidden" id="monto_pendiente" value="<?php echo $montoPendiente; ?>">
                                <input type="hidden" name="nuevo_monto_pendiente" id="nuevo_monto_pendiente">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" name="submitGuardar">Realizar Pago</button>
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
                function calcularNuevoMontoPendiente() {
                    // Obtener el monto del pago y el monto pendiente actual
                    var montoPago = parseFloat(document.getElementById("monto_pago").value);
                    var montoPendiente = parseFloat(document.getElementById("monto_pendiente").value);

                    if (isNaN(montoPago) || isNaN(montoPendiente)) {
                        alert("Ingrese un valor válido para el monto del pago y el monto pendiente.");
                        return false;
                    }

                    // Calcular el nuevo monto pendiente
                    var nuevoMontoPendiente = montoPendiente - montoPago;

                    // Actualizar el campo oculto con el nuevo monto pendiente
                    document.getElementById("nuevo_monto_pendiente").value = nuevoMontoPendiente;

                    return true; // Permite que el formulario se envíe
                }
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


            <?php include '../Modelo/o_scrips_generales.php'; ?>
</body>

</html>
