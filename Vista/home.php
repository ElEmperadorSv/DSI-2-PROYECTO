<?php 
    include '../Controlador/sesion.php';
    include '../Controlador/db_connection.php'; // Aquí debes incluir tu archivo de conexión a la base de datos

    // 1. Distribución de productos por categoría
    $queryProductos = "SELECT categoria_pd, COUNT(*) as total FROM productos_dsi GROUP BY categoria_pd";
    $resultProductos = mysqli_query($conn, $queryProductos);

    $productosLabels = [];
    $productosData = [];

    while ($row = mysqli_fetch_assoc($resultProductos)) {
        $productosLabels[] = $row['categoria_pd'];
        $productosData[] = $row['total'];
    }

    // 2. Distribución de clientes activos e inactivos
    $queryClientes = "SELECT estado_ct, COUNT(*) as total FROM clientes_dsi GROUP BY estado_ct";
    $resultClientes = mysqli_query($conn, $queryClientes);

    $clientesLabels = [];
    $clientesData = [];

    while ($row = mysqli_fetch_assoc($resultClientes)) {
        $clientesLabels[] = $row['estado_ct'];
        $clientesData[] = $row['total'];
    }

    // 3. Tipos de pago en créditos
    $queryCreditos = "SELECT tipo_pago, COUNT(*) as total FROM creditos_dsi GROUP BY tipo_pago";
    $resultCreditos = mysqli_query($conn, $queryCreditos);

    $creditosLabels = [];
    $creditosData = [];

    while ($row = mysqli_fetch_assoc($resultCreditos)) {
        $creditosLabels[] = $row['tipo_pago'];
        $creditosData[] = $row['total'];
    }

    // 4. Estado de los pagos
    $queryPagos = "SELECT estado_pago, COUNT(*) as total, fecha_pago FROM pagos_dsi GROUP BY estado_pago, fecha_pago";
    $resultPagos = mysqli_query($conn, $queryPagos);

    // Procesar los resultados para obtener datos de tiempo y pagos
    $pagosLabels = [];
    $pagosData = ['PENDIENTE' => [], 'REALIZADO' => [], 'EN MORA' => []];

    while ($row = mysqli_fetch_assoc($resultPagos)) {
        $fecha = $row['fecha_pago'];
        if (!in_array($fecha, $pagosLabels)) {
            $pagosLabels[] = $fecha;
        }
        $pagosData[$row['estado_pago']][] = $row['total'];
    }

    // Cerrar la conexión
    mysqli_close($conn);
?>

<head>
    <title>Inicio</title>
    <?php include '../Modelo/o_head.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
    <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / DASHBOARD </li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <h5>Distribución de productos por categoría</h5>
                            <canvas id="productosChart" style="max-width: 400px; max-height: 250px;"></canvas>
                        </div>
                        <div>
                            <h5>Tipos de pago en créditos</h5>
                            <canvas id="creditosChart" style="max-width: 400px; max-height: 250px;"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <h5>Distribución de clientes activos e inactivos</h5>
                            <canvas id="clientesChart" style="max-width: 400px; max-height: 250px;"></canvas>
                        </div>
                        <div>
                            <h5>Estado de los pagos</h5>
                            <canvas id="pagosChart" style="max-width: 400px; max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Termina la Funcionalidad de la Vista-->
            </div>
        </main>

        <?php include '../Modelo/o_scrips_generales.php'; ?>
    </div>
</body>
</html>

<script>
    // Datos recuperados desde PHP
    const productosLabels = <?php echo json_encode($productosLabels); ?>;
    const productosData = <?php echo json_encode($productosData); ?>;
    const clientesLabels = <?php echo json_encode($clientesLabels); ?>;
    const clientesData = <?php echo json_encode($clientesData); ?>;
    const creditosLabels = <?php echo json_encode($creditosLabels); ?>;
    const creditosData = <?php echo json_encode($creditosData); ?>;
    const pagosLabels = <?php echo json_encode($pagosLabels); ?>;
    const pagosData = <?php echo json_encode($pagosData); ?>;

    // Configuración de gráficos
    const ctxProductos = document.getElementById('productosChart').getContext('2d');
    const productosChart = new Chart(ctxProductos, {
        type: 'bar',
        data: {
            labels: productosLabels,
            datasets: [{
                label: 'Cantidad de productos',
                data: productosData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctxClientes = document.getElementById('clientesChart').getContext('2d');
    const clientesChart = new Chart(ctxClientes, {
        type: 'doughnut',
        data: {
            labels: clientesLabels,
            datasets: [{
                label: 'Clientes',
                data: clientesData,
                backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                borderWidth: 1
            }]
        }
    });

    const ctxCreditos = document.getElementById('creditosChart').getContext('2d');
    const creditosChart = new Chart(ctxCreditos, {
        type: 'pie',
        data: {
            labels: creditosLabels,
            datasets: [{
                label: 'Cantidad de créditos',
                data: creditosData,
                backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                borderWidth: 1
            }]
        }
    });

    const ctxPagos = document.getElementById('pagosChart').getContext('2d');
    const pagosChart = new Chart(ctxPagos, {
        type: 'line',
        data: {
            labels: pagosLabels,
            datasets: [{
                label: 'Pagos',
                data: pagosData['PENDIENTE'],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Realizados',
                data: pagosData['REALIZADO'],
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'En mora',
                data: pagosData['EN MORA'],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }
    });

</script>