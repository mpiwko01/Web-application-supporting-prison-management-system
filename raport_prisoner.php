<?php

include 'logowanie.php';
require('fpdf/fpdf.php'); 
require('tfpdf/tfpdf.php');

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$prisonerId = $_GET['id']; 

$sql = "SELECT * FROM prisoners WHERE `prisoner_id` = $prisonerId";
$result = $dbconn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $pdf = new tFPDF();
    $pdf->AddPage();
    $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $pdf->SetFont('DejaVu','', 13);
    $pdf->SetXY(10, 10);
    
   
    $pdf->Cell(0,13, "Raport szczegółowy", 0,1,'C'); //0-szerokosc komorki, 13-wysokosc, 0-brak ramki, 1-nowa linia po tekscie, C-wysrodkowane
    $pdf->Ln();
    $pdf->Cell(0,13, "Raport szczegółowy", 0,1,'C');

    foreach ($row as $key => $value) {
        $pdf->Ln();
        $pdf->Cell(40, 10, $key . ': ' . $value);
    }

    $filename = 'raport_' . $prisonerId . '.pdf';

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"'); 

    $pdf->Output('I');

} else {
    echo "Brak danych dla więźnia o ID: $prisonerId";
}
?>
