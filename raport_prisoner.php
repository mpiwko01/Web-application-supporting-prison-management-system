<?php

include 'logowanie.php';
require('fpdf/fpdf.php'); 
require('tfpdf/tfpdf.php');

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$prisonerId = $_GET['id']; 

$query = "SELECT prisoners.name, prisoners.surname, prisoners.birth_date, prisoners.street, prisoners.house_number, prisoners.city, prisoners.zip_code, crimes.description, crimes.art, prisoner_sentence.from_date, prisoner_sentence.to_date FROM prisoners INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN crimes ON crimes.crime_id = prisoner_sentence.crime_id WHERE prisoners.prisoner_id = '$prisonerId' AND prisoner_sentence.release_date IS NULL";

$result = mysqli_query($dbconn, $query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $surname = $row['surname'];
    $birthDate = $row['birth_date'];
    $street = $row['street'];
    $houseNumber = $row['house_number'];
    $city = $row['city'];
    $zipCode = $row['zip_code'];
    $crime = $row['description'];
    $art = $row['art'];
    $fromDate = $row['from_date'];
    $toDate = $row['to_date'];
   
    //$query_cur_sentence = "SELECT * FROM prisoner_sentence WHERE `prisoner_id`='$prisonerId' AND `release_date` IS NULL";
    //$result_cur_sentence = mysqli_query($dbconn, $query_cur_sentence);
    //$row_cur_sentence = mysqli_fetch_assoc($result_prisoner_sex);
    //$prisoner_sex = $row_prisoner_sex['sex'];

    $pdf = new tFPDF();
    $pdf->AddPage();
    $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $pdf->SetFont('DejaVu','', 8);
    $pdf->SetXY(10, 3);
   
    $pdf->Line(10, 10, $pdf->GetPageWidth() - 10, 10);

    $time = new DateTime();
    $format = 'Y-m-d'; 
    $time = $time->format($format);

    $date = new DateTime();
    $birthDate1 = new DateTime($birthDate);
    $format1 = 'Y';
    $birthYear = $birthDate1->format($format1);
    $age = $date->diff($birthDate1)->y;
    
    $image = 'img/blank.png'; 

    $pdf->Cell(0,5, "Data wystawienia: ".$time, 0,0,'L');
    $pdf->Cell(0,5, "Wystawiony przez:   " . $_SESSION['name'] . " " . $_SESSION['surname'] , 0,1,'R');
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('DejaVu','', 13);
    $pdf->Cell(0,7, "Raport szczegółowy", 0,1,'C');
    $pdf->Ln();

    $pdf->SetFont('DejaVu','', 11);
    $pdf->Cell(0,6, "Malopołski Zakład Karny", 0,1,'R');
    $pdf->Cell(0,6, "ul. Stroma 15", 0,1,'R');
    $pdf->Cell(0,6, "30-654 Kraków", 0,1,'R');
    $pdf->Cell(0,6, "Polska", 0,1,'R');
    $pdf->Ln();

    $pdf->Image($image, $pdf->GetPageWidth() - 10 - 55, 78, 55, 0, 'PNG', '', '', true, 300, '', false, false, 1, 'R');
   
    $pdf->Cell(70,8, "ID osadzonego:". ' '.$prisonerId, 0,1,'L');

    $pdf->Ln();
    $pdf->Cell(70,8, "Dane osobowe:", 0,1);
    $pdf->Cell(70,8, "Imię:". ' '.$name, 0,0);
    $pdf->Cell(70,8, "Nazwisko:". ' '.$surname, 0,1);
    $pdf->Cell(70,8, "Data urodzenia:". ' '.$birthDate, 0,0);
    $pdf->Cell(70,8, "Wiek:". ' '.$age, 0,0);

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(70,8, "Dane adresowe:", 0,1);
    $pdf->Cell(70,8, "Ulica:". ' '.$street, 0,0);
    $pdf->Cell(70,8, "Numer domu/mieszkania:". ' '.$houseNumber, 0,1);
    $pdf->Cell(70,8, "Miasto:". ' '.$city, 0,0);
    $pdf->Cell(70,8, "Kod pocztowy:". ' '.$zipCode, 0,0);

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(70,8, "Obecny wyrok:", 0,1);
    $pdf->Cell(70,8, "Czyn zabroniony:". ' '.$crime, 0,0);
    $pdf->Cell(70,8, "Art.". ' '.$art . " kk", 0,1);
    $pdf->Cell(70,8, "Data początkowa:". ' '.$fromDate, 0,0);
    $pdf->Cell(70,8, "Data końcowa:". ' '.$toDate, 0,0);



    $filename = 'raport_' . $prisonerId . '.pdf';

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"'); 

    $pdf->Output('I');

} else {
    echo "Brak danych dla więźnia o ID: $prisonerId";
}
?>
