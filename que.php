<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "pruebas";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Cargar la biblioteca TCPDF
require_once('../DSI_ONE/Complementos/tcpdf/examples/lang/spa.php');
require_once('../DSI_ONE/Complementos/tcpdf/tcpdf.php');

// Verificar si se ha enviado el formulario para crear un préstamo
if (isset($_POST['submit'])) {
    $nombreCliente = $_POST['nombre_cliente'];
    $monto = $_POST['monto'];
    $interes = $_POST['interes'];
    $cuotas = $_POST['cuotas'];

    // Insertar el préstamo en la tabla de préstamos
    $sql = "INSERT INTO prestamos (nombre_cliente, monto, interes, cuotas) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombreCliente, $monto, $interes, $cuotas);

    if ($stmt->execute()) {
        $prestamoId = $stmt->insert_id;
        $stmt->close();

        // Generar las hojas PDF para cada cuota
        for ($i = 1; $i <= $cuotas; $i++) {
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 10, "Nombre del cliente: $nombreCliente", 0, 1);
            $pdf->Cell(0, 10, "Monto: $monto", 0, 1);
            $pdf->Cell(0, 10, "Interés: $interes", 0, 1);
            $pdf->Cell(0, 10, "Número de cuota: $i", 0, 1);
            $pdf->Output(__DIR__ . "/cuota_$i.pdf", 'F');

            // Insertar la información de la cuota en la tabla de cuotas
            $sql = "INSERT INTO cuotas (prestamo_id, numero_cuota, estado) VALUES (?, ?, 'pendiente')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $prestamoId, $i);
            $stmt->execute();
            $stmt->close();
        }

        echo "El préstamo ha sido creado exitosamente.";
    } else {
        echo "Error al crear el préstamo: " . $stmt->error;
        $stmt->close();
    }
}

// Obtener la lista de préstamos
$sql = "SELECT * FROM prestamos";
$result = $conn->query($sql);

// Mostrar la tabla de préstamos
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Nombre Cliente</th><th>Monto</th><th>Interés</th><th>Número de Cuotas</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['nombre_cliente'] . "</td>";
        echo "<td>" . $row['monto'] . "</td>";
        echo "<td>" . $row['interes'] . "</td>";
        echo "<td>" . $row['cuotas'] . "</td>";

        // Obtener el número de cuotas para generar los enlaces a los archivos PDF
        $numeroCuotas = $row['cuotas'];
        for ($i = 1; $i <= $numeroCuotas; $i++) {
            $pdfUrl = "../DSI_ONE/Complementos/Creditos/DSI-001/cuota_$i.pdf";
            echo "<td><a href='$pdfUrl' target='_blank'>Ver Cuota $i</a></td>";
        }

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay préstamos disponibles.";
}

$conn->close();
?>

<!-- Formulario para crear un préstamo -->
<form method="post" action="">
    <label for="nombre_cliente">Nombre del Cliente:</label>
    <input type="text" name="nombre_cliente" id="nombre_cliente" required><br>

    <label for="monto">Monto:</label>
    <input type="number" name="monto" id="monto" required><br>

    <label for="interes">Interés:</label>
    <input type="number" name="interes" id="interes" required><br>

    <label for="cuotas">Número de Cuotas:</label>
    <input type="number" name="cuotas" id="cuotas" required><br>

    <input type="submit" name="submit" value="Crear Préstamo">
</form>