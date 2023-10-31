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

// Función para subir un archivo y obtener la ruta
function subirArchivo($archivo, $rutaDestino)
{
    $imagenNombre = $archivo['name'];
    $imagenTmp = $archivo['tmp_name'];
    $imagenDestino = $rutaDestino . $imagenNombre;

    if (move_uploaded_file($imagenTmp, $imagenDestino)) {
        return $imagenDestino;
    } else {
        return null;
    }
}

// Verificar si se ha enviado el formulario de guardar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitGuardar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('nombre_pd', 'descripcion_pd', 'stock_pd', 'categoria_pd', 'precio_pd');
    if (validarCampos($camposRequeridos)) {
        $nombreProducto = $_POST['nombre_pd'];
        $descripcionProducto = $_POST['descripcion_pd'];
        $stockProducto = $_POST['stock_pd'];
        $categoriaProducto = $_POST['categoria_pd'];
        $precioProducto = $_POST['precio_pd'];

        // Procesamiento de la imagen
        $imagenNombre = $_FILES['imagen']['name'];
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenDestino = '../Complementos/Imagenes/' . $imagenNombre;  // Ruta donde se guardará la imagen

        if (move_uploaded_file($imagenTmp, $imagenDestino)) {
            // La imagen se ha cargado correctamente, ahora puedes guardar la ruta en la base de datos

            // Consulta de inserción
            $query = "INSERT INTO productos_dsi (nombre_pd, descripcion_pd, stock_pd, categoria_pd, precio_pd, imagen) VALUES (?, ?, ?, ?, ?, ?)";

            // Preparar la consulta
            $stmt = $conn->prepare($query);

            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt) {
                // Vincular los parámetros de la consulta
                $stmt->bind_param("ssssss", $nombreProducto, $descripcionProducto, $stockProducto, $categoriaProducto, $precioProducto, $imagenDestino);

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    // La inserción fue exitosa
                    echo "Producto insertado correctamente.";

                    // Redireccionar al usuario a la página de gestión de productos
                    header("Location: ../Vista/gestion_productos.php");
                    exit();
                } else {
                    // Ocurrió un error al insertar el registro
                    echo "Error al insertar el registro en la base de datos: " . $stmt->error;
                }

                // Cerrar la declaración
                $stmt->close();
            } else {
                // Ocurrió un error al preparar la consulta
                echo "Error al preparar la consulta: " . $conn->error;
            }
        } else {
            echo "Error al mover la imagen al directorio de destino.";
        }
    } else {
        echo "No se han llenado todos los campos requeridos.";
    }
} else {
    echo "No se ha enviado el formulario correctamente.";
}

// Verificar si se ha enviado el formulario de actualizar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitActualizar'])) {
    // Verificar si todos los campos requeridos se han llenado
    $camposRequeridos = array('id_pd', 'nombre_pd', 'descripcion_pd', 'stock_pd', 'categoria_pd', 'precio_pd', 'estado_pd');
    if (validarCampos($camposRequeridos)) {
        // Obtener los datos actualizados del producto desde el formulario
        $id = $_POST['id_pd'];
        $nombreProducto = $_POST['nombre_pd'];
        $descripcionProducto = $_POST['descripcion_pd'];
        $stockProducto = $_POST['stock_pd'];
        $categoriaProducto = $_POST['categoria_pd'];
        $precioProducto = $_POST['precio_pd'];
        // Calcular el estado del producto
        $estadoProducto = ($stockProducto == 0) ? 'INACTIVO' : 'ACTIVO';

        // Calcular el estado del producto
        if ($stockProducto <= 0) {
            $estadoProducto = 'INACTIVO';
        } else {
            $estadoProducto = 'ACTIVO';
        }

        // Procesamiento de la imagen (solo si se proporciona una nueva imagen)
        if (!empty($_FILES['imagen']['name'])) {
            // Tu lógica para manejar la imagen
        } else {
            // Mantener la imagen actual si no se proporciona una nueva
            $imagenDestino = $_POST['imagen_actual_edit'];
        }

        // Consulta de actualización para los otros campos y el estado
        $query = "UPDATE productos_dsi SET nombre_pd = ?, descripcion_pd = ?, stock_pd = ?, categoria_pd = ?, precio_pd = ?, estado_pd = ? WHERE id_pd = ?";
        // Preparar la consulta
        $stmt = $conn->prepare($query);
        if ($stmt) {
            // Vincular los parámetros de la consulta
            $stmt->bind_param("ssssssi", $nombreProducto, $descripcionProducto, $stockProducto, $categoriaProducto, $precioProducto, $estadoProducto, $id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // La actualización fue exitosa
                echo "Producto actualizado correctamente.";

                // Redireccionar al producto a la página de gestión
                header("Location: ../Vista/gestion_productos.php", true, 303);
                exit();
            } else {
                echo "Error al actualizar el registro en la base de datos: " . $stmt->error;
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        echo "No se han llenado todos los campos requeridos para actualizar.";
    }
} else {
    echo "No se ha enviado el formulario correctamente.";
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
                                        echo "<td> $ " . $row['precio_pd'] . "</td>";
                                        echo "<td>" . $row['estado_pd'] . "</td>";
                                        echo '<td><img src="' . $row['imagen'] . '" alt="Imagen del producto" style="width: 100px; height: 100px;"></td>';
                                        echo '<td>
                                                <button class="btn btn-primary" onclick="cargarDatosProducto(' . $row['id_pd'] . ')" data-bs-toggle="modal" data-bs-target="#editProductoModal">Editar<i class="fas fa-pencil-alt" style="color: white;"></i></button>
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

                            <?php
                            // Consulta SQL para obtener las categorías
                            $query = "SELECT id_cat, nombre_cat FROM categorias_dsi";
                            $result = $conn->query($query);
                            ?>

                            <div class="mb-3 row">
                                <label for="categoria_pd" class="col-sm-2 col-form-label">Categoría</label>
                                <div class="col-sm-6">
                                    <select class="form-select form-control" id="categoria_pd" name="categoria_pd" required>
                                        <option value="" selected disabled>Seleccionar categoría</option>
                                        <?php
                                        // Generar las opciones dinámicamente
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["nombre_cat"] . '">' . $row["nombre_cat"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="nombre_pd" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="nombre_pd" name="nombre_pd" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_pd" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_pd" name="descripcion_pd" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="stock_pd" class="form-label">Stock</label>
                                        <input type="number" step="1.00" class="form-control" id="stock_pd" name="stock_pd" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="precio_pd" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control" id="precio_pd" name="precio_pd" placeholder="Ejemplo: 123.45" required>
                                    </div>
                                </div>
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
        <div class="modal fade" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductoModalLabel">Editar la información del Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                            <input type="hidden" id="id_pd_edit" name="id_pd">

                            <?php
                            // Consulta SQL para obtener las categorías
                            $query = "SELECT id_cat, nombre_cat FROM categorias_dsi";
                            $result = $conn->query($query);
                            ?>

                            <div class="mb-3 row">
                                <label for="categoria_pd_edit" class="col-sm-2 col-form-label">Categoría</label>
                                <div class="col-sm-6">
                                    <select class="form-select form-control" id="categoria_pd_edit" name="categoria_pd" required>
                                        <option value="" selected disabled>Seleccionar categoría</option>
                                        <?php
                                        // Generar las opciones dinámicamente
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["nombre_cat"] . '">' . $row["nombre_cat"] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-6">
                                    <label for="nombre_pd_edit" class="col-sm-10 col-form-label">Nombre del Producto</label>
                                    <input type="text" class="form-control" id="nombre_pd_edit" name="nombre_pd" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="descripcion_pd_edit" class="col-sm-2 col-form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion_pd_edit" name="descripcion_pd" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="stock_pd_edit" class="form-label">Stock</label>
                                        <input type="number" step="1.00" class="form-control" id="stock_pd_edit" name="stock_pd" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="precio_pd_edit" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control" id="precio_pd_edit" name="precio_pd" placeholder="Ejemplo: 123.45" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 text-center"> <!-- Agregado el estilo text-center para centrar -->
                                <label for="imagen_edit" class="form-label">Imagen Actual </label>
                                <img id="imagen_actual" src="" alt="Imagen actual del producto" style="width: 100px; height: 100px; display: inline-block;">
                            </div>

                            <div class="mb-3">
                                <label for="imagen_edit" class="form-label">Nueva Imagen</label>
                                <input type="file" class="form-control" id="imagen_edit" name="imagen" accept="image/*">
                                <input type="hidden" id="imagen_actual_edit" name="imagen_actual" value="">
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

        <!-------------- Script para el Editar Producto -------------->
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

                        // Update the input fields with the product data
                        $('#id_pd_edit').val(producto.id_pd);
                        $('#nombre_pd_edit').val(producto.nombre_pd);
                        $('#descripcion_pd_edit').val(producto.descripcion_pd);
                        $('#stock_pd_edit').val(producto.stock_pd);
                        $('#categoria_pd_edit').val(producto.categoria_pd);
                        $('#precio_pd_edit').val(producto.precio_pd);

                        // Mostrar la imagen actual del producto
                        $('#imagen_actual').attr('src', producto.imagen);

                        // Limpiar el campo de selección de archivo para la nueva imagen
                        $('#imagen_edit').val('');

                        // Update the hidden input with the current image URL
                        $('#imagen_actual_edit').val(producto.imagen);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            // Función para manejar la selección de una nueva imagen
            $('#imagen_edit').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagen_actual').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        </script>
        <!-------------- Script para el Editar Producto -------------->


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

</body>

</html>