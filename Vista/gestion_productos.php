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


// Verificar si se ha enviado el formulario de guardar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitGuardar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('nombre_pd', 'descripcion_pd', 'stock_pd', 'categoria_pd', 'precio_pd', 'imagen', 'estado_pd');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos del nuevo producto desde el formulario
        $nombreProducto = $_POST['nombre_pd'];
        $descripcionProducto = $_POST['descripcion_pd'];
        $stockProducto = $_POST['stock_pd'];
        $categoriaProducto = $_POST['categoria_pd'];
        $precioProducto = $_POST['precio_pd'];
        $imagenProducto = $_FILES['imagen']['name']; // Nombre del archivo de imagen
        $estadoProducto = $_POST['estado_pd'];

        // Ruta donde se guardará la imagen (ajústala según tu configuración)
        $rutaImagen = __DIR__ . '/../Complementos/Imagenes/' . $imagenProducto;

        // Consulta de inserción para productos
        $query = "INSERT INTO productos_dsi (nombre_pd, descripcion_pd, stock_pd, categoria_pd, precio_pd, imagen, estado_pd) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros
            $stmt->bind_param("sssssss", $nombreProducto, $descripcionProducto, $stockProducto, $categoriaProducto, $precioProducto, $imagenProducto, $estadoProducto);

            // Mover la imagen cargada al directorio deseado
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
                // Ejecutar la consulta
                $stmt->execute();

                // Verificar si la inserción fue exitosa
                if ($stmt->affected_rows > 0) {
                    // La inserción fue exitosa, realizar las acciones adicionales necesarias
                    // ...

                    // Redireccionar a la página de gestión de productos o a donde desees
                    header("Location: ../Vista/gestion_productos.php");
                    exit();
                } else {
                    // Ocurrió un error al insertar el nuevo registro
                    echo "Error al insertar el nuevo registro en la base de datos.";
                }
            } else {
                // Error al mover la imagen
                echo "Error al mover la imagen.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            // Ocurrió un error al preparar la consulta
            echo "Error al preparar la consulta.";
        }
    }
}

// Verificar si se ha enviado el formulario de actualizar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitActualizar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('nombre_pd', 'descripcion_pd', 'stock_pd', 'categoria_pd', 'precio_pd', 'estado_pd');
    if (validarCampos($camposRequeridos)) {
        // Obtener el ID del producto a actualizar desde el formulario
        $id = $_POST['id_pd'];

        // Obtener los datos actualizados del producto desde el formulario
        $nombreProducto = $_POST['nombre_pd'];
        $descripcionProducto = $_POST['descripcion_pd'];
        $stockProducto = $_POST['stock_pd'];
        $categoriaProducto = $_POST['categoria_pd'];
        $precioProducto = $_POST['precio_pd'];
        $estadoProducto = $_POST['estado_pd'];

        // Consulta de actualización para productos
        if (!empty($_FILES['imagen']['name'])) {
            // Si se proporciona una nueva imagen, procesarla y actualizar la URL de la imagen
            $imagenTmp = $_FILES['imagen']['tmp_name'];
            $imagenNombre = $_FILES['imagen']['name'];
            $imagenDestino = __DIR__ . '/../Complementos/Imagenes/' . $imagenNombre;

            if (move_uploaded_file($imagenTmp, $imagenDestino)) {
                $query = "UPDATE productos_dsi SET nombre_pd = ?, descripcion_pd = ?, stock_pd = ?, categoria_pd = ?, precio_pd = ?, estado_pd = ?, imagen = ? WHERE id_pd = ?";
            } else {
                // Error al mover la nueva imagen
                echo "Error al mover la nueva imagen.";
                exit();
            }
        } else {
            // Si no se proporciona una nueva imagen, actualizar sin cambiar la imagen
            $query = "UPDATE productos_dsi SET nombre_pd = ?, descripcion_pd = ?, stock_pd = ?, categoria_pd = ?, precio_pd = ?, estado_pd = ? WHERE id_pd = ?";
        }

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            if (!empty($_FILES['imagen']['name'])) {
                // Si se proporciona una nueva imagen, vincular la URL de la imagen al último parámetro
                $stmt->bind_param("sssssssi", $nombreProducto, $descripcionProducto, $stockProducto, $categoriaProducto, $precioProducto, $estadoProducto, $imagenDestino, $id);
            } else {
                // Si no se proporciona una nueva imagen, vincular los parámetros sin la URL de la imagen
                $stmt->bind_param("ssssssi", $nombreProducto, $descripcionProducto, $stockProducto, $categoriaProducto, $precioProducto, $estadoProducto, $id);
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                // La actualización fue exitosa, realizar las acciones adicionales necesarias
                // ...

                // Redireccionar al producto a la página de gestión
                header("Location: ../Vista/gestion_productos.php");
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

    }
}

?>

<head>
    <title>Gestión Productos</title>
    <?php include '../Modelo/o_head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include '../Modelo/o_menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Gestión de Productos</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active"> DSI ONE / <a href="../Vista/home.php"> Dashboard </a></li>
                </ol>

                <!-- Inicio de la Funcionalidad de la Vista-->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                     productos con acceso a Sistema
                        <div style="float: right;">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductoModal"><i class="fa-solid fa-circle-plus"></i> Nuevo Producto</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Producto</th>
                                    <th>Descripción</th>
                                    <th>Stock</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Imagen</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM productos_dsi";
                                $result = $conn->query($query);
                                
                                // Verificar si se encontraron resultados
                                if ($result->num_rows > 0) {
                                    // Iterar sobre los resultados y generar las filas de la tabla
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id_pd'] . "</td>";
                                        echo "<td>" . $row['nombre_pd'] . "</td>";
                                        echo "<td>" . $row['descripcion_pd'] . "</td>";
                                        echo "<td>" . $row['stock_pd'] . "</td>";
                                        echo "<td>" . $row['categoria_pd'] . "</td>";
                                        echo "<td>" . $row['precio_pd'] . "</td>";
                                        echo "<td>" . $row['estado_pd'] . "</td>";
                                        echo '<td><img src="' . $row['imagen'] . '" alt="Imagen del producto"></td>';
                                        echo '<td>
                                                <button class="btn btn-primary" onclick="cargarDatosProducto(' . $row['id_pd'] . ')" data-bs-toggle="modal" data-bs-target="#editProductoModal">Editar <i class="fas fa-pencil-alt" style="color: white;"></i></button>
                                            </td>';
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9'>No se encontraron registros en la tabla productos.</td></tr>";
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

        <!------------------------ Modal: Crear Nuevo Producto ------------------------>
        <div class="modal fade" id="addProductoModal" tabindex="-1" aria-labelledby="addProductoModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductoModal">Crear Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nombre_pd" class="form-label">Nombre Producto</label>
                                <input type="text" class="form-control" id="nombre_pd" name="nombre_pd" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_pd" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_pd" name="descripcion_pd" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="stock_pd" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock_pd" name="stock_pd" required>
                            </div>

                            <?php
                            //Consulta SQL para obtener las categorías
                            $query = "SELECT id_cat, nombre_cat FROM categorias_dsi";
                            $result = $conn->query($query);
                            ?>
                            <div class="mb-3">
                                <label for="categoria_pd" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria_pd" name="categoria_pd" required>
                                    <?php
                                    // Generar las opciones dinámicamente
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row["id_cat"] . '">' . $row["nombre_cat"] . '</option>';
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="precio_pd" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="precio_pd" name="precio_pd" required>
                            </div>

                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" required>
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
        <!------------------------ Modal: Crear Nuevo producto ------------------------>

        <!------------------------ Modal: Editar Información de Producto ------------------------>
        <div class="modal fade" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductoModal">Editar la información del Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <input type="hidden" id="id_pd" name="id_pd">

                        <div class="mb-3">
                            <label for="nombre_pd" class="form-label">Nombre Producto</label>
                            <input type="text" class="form-control" id="nombre_pd" name="nombre_pd" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_pd" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion_pd" name="descripcion_pd" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="stock_pd" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock_pd" name="stock_pd" required>
                        </div>
                        
                        <?php
                            //Consulta SQL para obtener las categorías
                            $query = "SELECT id_cat, nombre_cat FROM categorias_dsi";
                            $result = $conn->query($query);


                        ?>
                        <div class="mb-3">
                            <label for="categoria_pd" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria_pd" name="categoria_pd" required>
                                <?php
                                // Generar las opciones dinámicamente
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row["id_cat"] . '">' . $row["nombre_cat"] . '</option>';
                                    }
                                }
                                // Cerrar la conexión
                                $conn->close();
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="precio_pd" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="precio_pd" name="precio_pd" required>
                        </div>

                        <div class="mb-3">
                            <label for="estado_pd" class="form-label">Estado</label>
                            <select class="form-select" id="estado_pd" name="estado_pd" required>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen">
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
        <!------------------------ Modal: Editar Información de Producto ------------------------>

        <script>
            function cargarDatosProducto(id_pd) {
                $.ajax({
                    url: '../Controlador/datos_producto.php',
                    method: 'POST',
                    data: {
                        id_pd: id_pd
                    },
                    success: function(response) {
                        // Parsear la respuesta JSON
                        var producto = JSON.parse(response);

                        $('#editProductoModal #id_pd').val(producto.id_pd);
                        $('#editProductoModal #nombre_pd').val(producto.nombre_pd);
                        $('#editProductoModal #descripcion_pd').val(producto.descripcion_pd);
                        $('#editProductoModal #stock_pd').val(producto.stock_pd);
                        $('#editProductoModal #categoria_pd').val(producto.categoria_pd);
                        $('#editProductoModal #precio_pd').val(producto.precio_pd);
                     

                        // Cargar los valores posibles de estado en el combo box
                        var selectEstado = $('#editProductoModal #estado_pd');
                        selectEstado.empty(); // Limpiar opciones existentes

                        // Definir los estados disponibles
                        var estadosDisponibles = ["ACTIVO", "INACTIVO"];

                        // Iterar sobre los estados y agregarlos al combo box
                        for (var i = 0; i < estadosDisponibles.length; i++) {
                            var estado = estadosDisponibles[i];
                            var selected = (estado === producto.estado_pd) ? 'selected' : '';
                            selectEstado.append('<option value="' + estado + '" ' + selected + '>' + estado + '</option>');
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });

            }
        </script>


</body>

</html>