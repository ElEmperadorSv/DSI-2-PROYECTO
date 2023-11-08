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
$pdf->Cell(0, 10, 'Registros de clientes', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 8); // Restaurar el tamaño de fuente para los datos

// Obtener los datos de la tabla clientes_dsi
$query = "SELECT * FROM clientes_dsi";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Crear la tabla con un borde sólido alrededor de las celdas
    $html = '<table border="1">';
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
    $html .= '<th>Estado</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Iterar sobre los resultados y generar las filas de la tabla
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id_ct'] . '</td>';
        $html .= '<td>' . $row['nombre_ct'] . '</td>';
        $html .= '<td>' . $row['apellido_ct'] . '</td>';
        $html .= '<td>' . $row['dui_ct'] . '</td>';
        $fecha_nac = date('d-m-Y', strtotime($row['fecha_nac_ct']));
        $html .= '<td>' . $fecha_nac . '</td>';
        $html .= '<td>' . $row['telefono_ct'] . '</td>';
        $html .= '<td>' . $row['email_ct'] . '</td>';
        $html .= '<td>' . $row['direccion_ct'] . '</td>';
        $html .= '<td>' . $row['estado_ct'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('Clientes.pdf', 'D');
} else {
    echo "No se encontraron registros en la tabla clientes_dsi.";
}

// Cerrar la conexión
$conn->close();
?>
