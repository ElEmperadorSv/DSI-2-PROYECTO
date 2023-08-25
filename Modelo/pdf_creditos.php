<?php
require_once('../Complementos/tcpdf/tcpdf.php');
include "../Controlador/db_connection.php";

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Agregar una página
$pdf->AddPage();

// Establecer el contenido del encabezado y pie de página
$pdf->setHeaderData('', 0, '', '');
$pdf->setFooterData();

// Establecer el formato del encabezado y pie de página
$pdf->setHeaderFont(Array('helvetica', '', 10));
$pdf->setFooterFont(Array('helvetica', '', 8));

// Establecer los márgenes
$pdf->SetMargins(15, 15, 15);

// Establecer el espaciado entre líneas
$pdf->setCellPaddings(0, 2, 0, 2);

// Establecer el estilo de fuente y tamaño para el contenido
$pdf->SetFont('helvetica', '', 10);

// Agregar título a la tabla
$pdf->Cell(0, 10, 'Registros de créditos', 0, 1, 'C');

// Obtener los datos de la tabla clientes_dsi
$query = "SELECT * FROM creditos_dsi";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Crear la tabla
    $html = '<table>';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>ID</th>';
    $html .= '<th>N° Crédito</th>';
    $html .= '<th>DUI</th>';
    $html .= '<th>Nombre Completo</th>';
    $html .= '<th>Monto</th>';
    $html .= '<th>Tipo Pago</th>';
    $html .= '<th>Fecha Inicio</th>';
    $html .= '<th>Fecha Fin</th>';
    $html .= '<th>Plazo</th>';
    $html .= '<th>Interes</th>';
    $html .= '<th>Monto Total</th>';
    $html .= '<th>Cuota</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Iterar sobre los resultados y generar las filas de la tabla
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['num_credito'] . '</td>';
        $html .= '<td>' . $row['dui'] . '</td>';
        $html .= '<td>' . $row['nombre_completo'] . '</td>';
        $html .= '<td>' . $row['monto'] . '</td>';
        $html .= '<td>' . $row['tipo_pago'] . '</td>';
        $fecha_ini = date('d-m-Y', strtotime($row['fecha_ini']));
        $html .= '<td>' . $fecha_ini . '</td>';
        $fecha_fin = date('d-m-Y', strtotime($row['fecha_fin']));
        $html .= '<td>' . $fecha_fin . '</td>';
        $html .= '<td>' . $row['plazo'] . '</td>';
        $html .= '<td>' . $row['interes'] . '</td>';
        $html .= '<td>' . $row['monto_total'] . '</td>';
        $html .= '<td>' . $row['monto_pendiente'] . '</td>';
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
