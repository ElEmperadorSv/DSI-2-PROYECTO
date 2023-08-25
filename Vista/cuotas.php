<?php
include "../Controlador/db_connection.php";
session_start();

// Verificar si la sesión no está activa
if (!isset($_SESSION['username'])) {
    // Redireccionar al usuario a la página de inicio de sesión
    header("Location: ../index.php");
    exit();
}

// Cargar la biblioteca TCPDF
require_once('../DSI_ONE/Complementos/tcpdf/examples/lang/spa.php');
require_once('../DSI_ONE/Complementos/tcpdf/tcpdf.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestión Cuotas</title>

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
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClienteModal"><i class="fa-solid fa-circle-plus"></i>Agregar Cliente</button>
                            </div>
                        </div>
                        <div class="card-body">





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