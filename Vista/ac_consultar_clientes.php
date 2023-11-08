<?php 
include "../Controlador/db_connection.php";
session_start();

// Verificar si la sesión no está activa
if (!isset($_SESSION['username'])) {
    // Redireccionar al usuario a la página de inicio de sesión
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Consultar Clientes</title>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.7/datatables.min.css" rel="stylesheet">
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Consulta de Clientes Activos</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> DASHBOARD </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-filter me-1"></i>
                        Filtros
                    </div>
                    
                    <style>
                        .form-group {
                            margin-right: 10px;
                        }
                    </style>

                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="form-group d-flex align-items-center" style="flex: 1;">
                            <label for="filtroDUI" class="form-label">DUI</label>
                            <input type="text" class="form-control" id="filtroDUI" name="filtroDUI">
                        </div>
                        <div class="form-group d-flex align-items-center" style="flex: 1;">
                            <label for="filtroNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="filtroNombre" name="filtroNombre">
                        </div>
                        <div class="form-group d-flex align-items-center" style="flex: 1;">
                            <label for="filtroEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="filtroEmail" name="filtroEmail">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center"> 
                        <button class="form-group mb-3 btn btn-sm btn-primary mr-2" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
                        <button class="form-group mb-3 btn btn-sm btn-secondary" id="btnLimpiar"><i class="fas fa-eraser"></i> Limpiar</button>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Clientes Inscritos
                        <div style="float: right;">
                            <!--<button class="btn btn-danger" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Exportar a PDF</button>-->
                            <button class="btn btn-success" onclick="exportToExcel()"><i class="far fa-file-excel"></i> Exportar a Excel</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive" style="overflow-x: auto;">
                        <table id="datatablesSimple" class="display" style="width: 100%; table-layout: auto;">
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
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                var table = $('#datatablesSimple').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    "columns": [
                        { "data": "id_ct" },
                        { "data": "dui_ct" },
                        { "data": "nombre_ct" },
                        { "data": "apellido_ct" },
                        { "data": "fecha_nac_ct" },
                        { "data": "email_ct" },
                        { "data": "telefono_ct" },
                        { "data": "direccion_ct" },
                        { "data": "estado_ct" }
                    ]
                });

                $('#btnBuscar').click(function () {
                    var filtroDUI = $('#filtroDUI').val();
                    var filtroNombre = $('#filtroNombre').val();
                    var filtroEmail = $('#filtroEmail').val();

                    // Verificar si al menos uno de los filtros tiene un valor
                    if (filtroDUI !== '' || filtroNombre !== '' || filtroEmail !== '') {
                        // Realiza una solicitud AJAX al servidor
                        $.ajax({
                            url: '../Controlador/buscar_clientes.php',
                            method: 'POST',
                            data: {
                                filtroDUI: filtroDUI,
                                filtroNombre: filtroNombre,
                                filtroEmail: filtroEmail
                            },
                            dataType: 'json',
                            success: function (data) {
                                // Limpia la tabla de clientes inscritos
                                table.clear().draw();

                                // Agrega los resultados a la tabla
                                if (data.length > 0) {
                                    table.rows.add(data).draw();
                                }
                            }
                        });
                    }
                });

                $('#btnLimpiar').click(function () {
                    $('#filtroDUI').val('');
                    $('#filtroNombre').val('');
                    $('#filtroEmail').val('');
                    table.clear().draw();
                });
            });
        </script>

<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.1.1/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    function exportToExcel() {
        // Crear un nuevo libro de Excel
        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet('Clientes');

        // Agregar los títulos de las columnas
        worksheet.columns = [
            { header: 'ID', key: 'id_ct' },
            { header: 'DUI', key: 'dui_ct' },
            { header: 'Nombre', key: 'nombre_ct' },
            { header: 'Apellido', key: 'apellido_ct' },
            { header: 'Fecha de Nacimiento', key: 'fecha_nac_ct' },
            { header: 'Email', key: 'email_ct' },
            { header: 'Teléfono', key: 'telefono_ct' },
            { header: 'Dirección', key: 'direccion_ct' },
            { header: 'Estado', key: 'estado_ct' }
        ];

        // Obtener los datos de la tabla
        var table = document.getElementById('datatablesSimple');
        var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Agregar los datos a las filas del libro de Excel
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var rowData = [];

            for (var j = 0; j < row.cells.length; j++) {
                rowData.push(row.cells[j].textContent);
            }

            worksheet.addRow(rowData);
        }

        // Guardar el archivo Excel
        workbook.xlsx.writeBuffer().then(function (data) {
            var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            saveAs(blob, 'Clientes.xlsx');
        });
    }

    // Script para exportar la tabla a un PDF (si tienes una página que genera el PDF)
    function exportToPDF() {
        window.location.href = '../Modelo/pdf_consulta_clientes.php';
    }
</script>

    </body>
</html>
