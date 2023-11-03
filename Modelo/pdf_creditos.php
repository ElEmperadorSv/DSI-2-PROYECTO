<?php
require_once('../Complementos/TCPDF-main/TCPDF-main/tcpdf.php');
include "../Controlador/db_connection.php";

// Crear una nueva instancia de TCPDF con orientación horizontal
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');

// Establecer los márgenes
$pdf->SetMargins(15, 15, 15);

// Agregar una página
$pdf->AddPage();

// Establecer el estilo de fuente y tamaño para el contenido
$pdf->SetFont('helvetica', '', 8); // Reducir el tamaño de fuente

// Agregar título a la tabla con un tamaño de fuente más grande y negrita
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Registros de créditos', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 8); // Restaurar el tamaño de fuente para los datos

// Obtener los datos de la tabla clientes_dsi
$query = "SELECT * FROM creditos_dsi";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Crear la tabla con un borde sólido alrededor de las celdas
    $html = '<table border="1">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>ID</th>';
    $html .= '<th>DUI</th>';
    $html .= '<th>Nombre Completo</th>';
    $html .= '<th>N° Crédito</th>';
    $html .= '<th>Producto</th>';
    $html .= '<th>Cantidad Producto</th>';
    $html .= '<th>Monto</th>';
    $html .= '<th>Interes</th>';
    $html .= '<th>Plazo</th>';
    $html .= '<th>Monto Total</th>';
    $html .= '<th>Cuota</th>';
    $html .= '<th>Monto Pendiente</th>';
    $html .= '<th>Tipo Pago</th>';
    $html .= '<th>Fecha Inicio</th>';
    $html .= '<th>Fecha Fin</th>';
    $html .= '<th>Estado</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Iterar sobre los resultados y generar las filas de la tabla
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['dui_ct'] . '</td>';
        $html .= '<td>' . $row['cliente'] . '</td>';
        $html .= '<td>' . $row['num_credito'] . '</td>';
        $html .= '<td>' . $row['producto'] . '</td>';
        $html .= '<td>' . $row['cantidad_producto'] . '</td>';
        $html .= '<td>' . $row['monto'] . '</td>';
        $html .= '<td>' . $row['interes'] . '</td>';
        $html .= '<td>' . $row['plazo'] . '</td>';
        $html .= '<td>' . $row['monto_total'] . '</td>';
        $html .= '<td>' . $row['cuota'] . '</td>';
        $html .= '<td>' . $row['monto_pendiente'] . '</td>';
        $html .= '<td>' . $row['tipo_pago'] . '</td>';
        $fecha_ini = date('d-m-Y', strtotime($row['fecha_ini']));
        $html .= '<td>' . $fecha_ini . '</td>';
        $fecha_fin = date('d-m-Y', strtotime($row['fecha_fin']));
        $html .= '<td>' . $fecha_fin . '</td>';
        $html .= '<td>' . $row['estado_credito'] . '</td>';    
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('Creditos_Clientes.pdf', 'D');
} else {
    echo "No se encontraron registros en la tabla clientes_dsi.";
}

// Cerrar la conexión
$conn->close();
?>
