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
$pdf->Cell(0, 10, 'Registros de clientes', 0, 1, 'C');

// Obtener los datos de la tabla clientes_dsi
$query = "SELECT * FROM clientes_dsi";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Crear la tabla
    $html = '<table>';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>ID</th>';
    $html .= '<th>Nombre</th>';
    $html .= '<th>Apellido</th>';
    $html .= '<th>DUI</th>';
    $html .= '<th>Fecha de Nacimiento</th>';
    $html .= '<th>Teléfono</th>';
    $html .= '<th>Email</th>';
    $html .= '<th>Dirección</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Iterar sobre los resultados y generar las filas de la tabla
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['nombre'] . '</td>';
        $html .= '<td>' . $row['apellido'] . '</td>';
        $html .= '<td>' . $row['dui'] . '</td>';
        $fecha_nac = date('d-m-Y', strtotime($row['fecha_nac']));
        $html .= '<td>' . $fecha_nac . '</td>';
        $html .= '<td>' . $row['telefono'] . '</td>';
        $html .= '<td>' . $row['email'] . '</td>';
        $html .= '<td>' . $row['direccion'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('clientes.pdf', 'D');
} else {
    echo "No se encontraron registros en la tabla clientes_dsi.";
}

// Cerrar la conexión
$conn->close();
?>
