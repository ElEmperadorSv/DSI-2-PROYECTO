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
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw" aria-hidden="true"></i> <?php echo $_SESSION['username']; ?></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="../Vista/opciones_usuario.php">Opciones</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" id="btnLogout" href="../Controlador/logout.php">Cerrar Sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>


<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">

                    <!-- Inicio -->
                    <div class="sb-sidenav-menu-heading">General</div>
                    <a class="nav-link" href="../Vista/home.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-home-lg"></i></div>
                        Dashboard
                    </a>
                    <!------------------------------------------- Administración de Sistema ------------------------------------------->
                    <!-- Gestionar Usuarios -->
                    <div class="sb-sidenav-menu-heading">Administración</div>
                    <a class="nav-link" href="../Vista/admin_usuarios.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-home-lg"></i></div>
                        Gestionar Usuarios
                    </a>

                    <a class="nav-link" href="../Vista/admin_roles.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-home-lg"></i></div>
                        Gestionar Roles
                    </a>

                    <!-- Gestionar Productos -->

                    <a class="nav-link" href="../Vista/gestion_productos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-home-lg"></i></div>
                        Gestionar Productos
                    </a>
                    <!------------------------------------------- Administración de Sistema ------------------------------------------->

                    <!-- Gestionar Clientes -->
                    <div class="sb-sidenav-menu-heading">Operaciones</div>
                    <a class="nav-link" href="../Vista/aa_crear_cliente.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                        Gestionar Clientes
                    </a>

                    <!-- Gestionar Creditos -->
                    <div class="sb-sidenav-menu-heading">Operaciones</div>
                    <a class="nav-link" href="../Vista/../Vista/ba_crear_credito.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                        Gestionar Créditos
                    </a>

                    <!-- Gestionar Pagos -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePagos" aria-expanded="false" aria-controls="collapsePagos">
                        <div class="sb-nav-link-icon"><i class="fas fa-hand-holding-usd"></i></div>
                        Gestionar Pagos
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePagos" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Vista/ca_realizar_pago.php">Realizar Pago</a>
                            <a class="nav-link" href="../Vista/cb_consultar_pagos.php">Consultar Pagos</a>
                            <a class="nav-link" href="../Vista/cc_pagos_enmora.php">Pagos en Mora</a>
                        </nav>
                    </div>

                    <!-- Reportes -->
                    <div class="sb-sidenav-menu-heading">Reportes</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReportes" aria-expanded="false" aria-controls="collapseReportes">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                        Reportes
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseReportes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Vista/ct_crear_cliente.php">Clientes Inscritos</a>
                            <a class="nav-link" href="../Vista/ct_crear_cliente.php">Créditos Activos</a>
                            <a class="nav-link" href="../Vista/ct_crear_cliente.php">Pagos Pendientes</a>
                            <a class="nav-link" href="../Vista/ct_crear_cliente.php">Pagos en Mora</a>
                        </nav>
                    </div>

                    <!-- Información de Usuario -->
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Sesión iniciada como:</div>
                <?php echo $_SESSION['username']; ?>
            </div>
        </nav>
    </div>