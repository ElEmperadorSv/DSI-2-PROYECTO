<?php
include "../Controlador/db_connection.php";

// Verificar si se ha enviado el formulario de eliminación del cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'])) {
    // Obtener el ID del cliente desde el formulario
    $cliente_id = $_POST['cliente_id'];

    // Consulta para obtener el número máximo de ID en la tabla
    $consulta_max_id = "SELECT MAX(id) FROM clientes_dsi";
    $stmt_max_id = $conn->prepare($consulta_max_id);
    $stmt_max_id->execute();
    $stmt_max_id->bind_result($max_id);
    $stmt_max_id->fetch();
    $stmt_max_id->close();

    // Consulta para eliminar el cliente
    $consulta_eliminar = "DELETE FROM clientes_dsi WHERE id = ?";
    $stmt_eliminar = $conn->prepare($consulta_eliminar);
    $stmt_eliminar->bind_param("i", $cliente_id);

    // Iniciar una transacción para asegurar la consistencia de los datos
    $conn->begin_transaction();

    // Ejecutar la eliminación del cliente
    $stmt_eliminar->execute();

    // Verificar si la eliminación fue exitosa
    if ($stmt_eliminar->affected_rows > 0) {
        // Generar el nuevo ID incrementando el máximo ID existente en 1
        $nuevo_id = $max_id + 1;

        // Confirmar los cambios realizados en la transacción
        $conn->commit();

        // Enviar una respuesta exitosa al cliente
        echo "Cliente eliminado exitosamente. Nuevo ID generado: " . $nuevo_id;
    } else {
        // Ocurrió un error al eliminar el registro
        echo "Error al eliminar el registro en la base de datos.";
    }

    // Cerrar las declaraciones
    $stmt_eliminar->close();

    // Cerrar la conexión
    $conn->close();
}
