<?php

    include 'logowanie.php'; 
    require('fpdf/fpdf.php');
    require('tfpdf/tfpdf.php');

    $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

    $pdf = new tFPDF();
    $pdf->AddPage();
    $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $pdf->SetFont('DejaVu','', 8);
    $pdf->SetXY(10, 3);

    $pdf->Line(10, 10, $pdf->GetPageWidth() - 10, 10);

    $time = new DateTime();
    $format = 'Y-m-d'; 
    $time = $time->format($format);

    $pdf->Cell(0,5, "Data wystawienia: ".$time, 0,0,'L');
    $pdf->Cell(0,5, "Wystawiony przez:   " . $_SESSION['name'] . " " . $_SESSION['surname'] , 0,1,'R');
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('DejaVu','', 13);
    $pdf->Cell(0,7, "Raport statystyczny", 0,1,'C');
    $pdf->Ln();

    $pdf->SetFont('DejaVu','', 11);
    $pdf->Cell(0,6, "Malopołski Zakład Karny", 0,1,'R');
    $pdf->Cell(0,6, "ul. Stroma 15", 0,1,'R');
    $pdf->Cell(0,6, "30-654 Kraków", 0,1,'R');
    $pdf->Cell(0,6, "Polska", 0,1,'R');
    $pdf->Ln();

   
    function tableLabelFor2($pdf, $label1, $label2, $width1, $width2) {

        $pdf->SetFont('DejaVu','', 10);
        
        $pdf->Cell($width1, 8, $label1, 1, 0, 'C');
        $pdf->Cell($width2, 8, $label2, 1, 0, 'C');
        $pdf->Ln(); 
    }

    function tableLabelFor1($pdf, $label1, $width1) {

        $pdf->SetFont('DejaVu','', 10);
    
        $pdf->Cell($width1, 8, $label1, 1, 0, 'C');
        $pdf->Ln(); 
    }

    function tableLabelFor4($pdf, $label1, $label2, $label3, $label4, $width1, $width2, $width3, $width4) {

        $pdf->SetFont('DejaVu','', 10);
    
        $pdf->Cell($width1, 8, $label1, 1, 0, 'C');
        $pdf->Cell($width2, 8, $label2, 1, 0, 'C');
        $pdf->Cell($width3, 8, $label3, 1, 0, 'C');
        $pdf->Cell($width4, 8, $label4, 1, 0, 'C');
        $pdf->Ln(); 
    }

    function AddRow($data) {
        
        $this->Cell(40, 10, $data[0], 1);
        $this->Cell(40, 10, $data[1], 1);
        $this->Cell(40, 10, $data[2], 1);
        $this->Ln();
    }

    //szerokosci okien
    $width = ($pdf->GetPageWidth() - 20);
    $width0_5 = $width/2;
    $width0_25 = $width/4;
    $width0_75 = 3*$width/4;

    $query_count_all = "SELECT COUNT(*) as total FROM prisoners WHERE `in_prison` = 1"; 
    $result_count_all = mysqli_query($dbconn, $query_count_all);
    $row_count_all = mysqli_fetch_array($result_count_all);
    $all_prisoners = $row_count_all['total'];

    $query_count_m = "SELECT COUNT(*) as m FROM prisoners where sex='M' AND `in_prison` = 1";
    $result_count_m = mysqli_query($dbconn, $query_count_m);
    $row_count_m = mysqli_fetch_array($result_count_m);
    $all_m = $row_count_m['m'];

    $query_count_f = "SELECT COUNT(*) as f FROM prisoners where sex='F' AND `in_prison` = 1"; 
    $result_count_f = mysqli_query($dbconn, $query_count_f);
    $row_count_f = mysqli_fetch_array($result_count_f);
    $all_f = $row_count_f['f'];

    tableLabelFor2($pdf, 'Liczba osadzonych', $all_prisoners, $width0_5, $width0_5);
    tableLabelFor2($pdf, 'Liczba kobiet', $all_f, $width0_5, $width0_5);
    tableLabelFor2($pdf, 'Liczba mężczyzn', $all_m, $width0_5, $width0_5);

    $pdf->Ln();

    $query_count_all_reoffenders = "SELECT COUNT(*) as total_r FROM prisoners WHERE `is_reoffender` = 1 AND `in_prison` = 1"; 
    $result_count_all_reoffenders = mysqli_query($dbconn, $query_count_all_reoffenders);
    $row_count_all_reoffenders = mysqli_fetch_array($result_count_all_reoffenders);
    $all_reoffenders = $row_count_all_reoffenders['total_r'];

    $query_count_all_reoffenders_f = "SELECT COUNT(*) as total_rf FROM prisoners WHERE `is_reoffender` = 1 AND `in_prison` = 1 AND `sex`='F'"; 
    $result_count_all_reoffenders_f = mysqli_query($dbconn, $query_count_all_reoffenders_f);
    $row_count_all_reoffenders_f = mysqli_fetch_array($result_count_all_reoffenders_f);
    $all_reoffenders_f = $row_count_all_reoffenders_f['total_rf'];

    $query_count_all_reoffenders_m = "SELECT COUNT(*) as total_rm FROM prisoners WHERE `is_reoffender` = 1 AND `in_prison` = 1 AND `sex` = 'M'"; 
    $result_count_all_reoffenders_m = mysqli_query($dbconn, $query_count_all_reoffenders_m);
    $row_count_all_reoffenders_m = mysqli_fetch_array($result_count_all_reoffenders_m);
    $all_reoffenders_m = $row_count_all_reoffenders_m['total_rm'];

    tableLabelFor2($pdf, 'Liczba recydywistów', $all_reoffenders, $width0_5, $width0_5);
    tableLabelFor2($pdf, 'Liczba kobiet', $all_reoffenders_f, $width0_5, $width0_5);
    tableLabelFor2($pdf, 'Liczba mężczyzn', $all_reoffenders_m, $width0_5, $width0_5);

    $pdf->Ln();
    $pdf->Ln();

    tableLabelFor1($pdf, "Odział kobiet", $width);
    tableLabelFor2($pdf, 'Numer celi', 'Osadzone', $width0_25, $width0_75);

    $prisoners = [];

    for ($i=1; $i<13; $i++) {

        if($i == 7) {
            $pdf->Ln();
            tableLabelFor1($pdf, "Odział mężczyzn", $width);
            tableLabelFor2($pdf, 'Numer celi', 'Osadzeni', $width0_25, $width0_75);
        }

        $query_[$i] = "SELECT COUNT(*) as count FROM prisoners INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id WHERE `to_date` IS NULL AND cell_history.cell_nr = '$i'"; 

        $result_[$i] = mysqli_query($dbconn, $query_[$i]);

        $row_count_[$i] = mysqli_fetch_array($result_[$i]);
        $count_[$i] = $row_count_[$i]['count'];

        if ($count_[$i] == 0) {
            $prisoners[$i] = '';
            tableLabelFor2($pdf, $i, $prisoners[$i], $width0_25, $width0_75);
        }
        else {

            $query = "SELECT prisoners.name, prisoners.surname FROM prisoners INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id WHERE `to_date` IS NULL AND cell_history.cell_nr = '$i'"; 

            $result = mysqli_query($dbconn, $query);

            $names = [];
            $surnames = [];
    
            while ($row = mysqli_fetch_array($result)) {
                $names[] = $row['name'];
                $surnames[] = $row['surname'];
            }

            $prisonerInfo = [];
            foreach ($names as $index => $name) $prisonerInfo[] = $name . ' ' . $surnames[$index];
            $prisoners[$i] = $prisonerInfo;

            tableLabelFor2($pdf, $i, implode(', ', $prisoners[$i]), $width0_25, $width0_75);
        } 
    }

    $pdf->Ln();
    $pdf->Ln();

    tableLabelFor4($pdf, "ID", "Osadzony", "Czyn zabroniony", "Art. kk", $width0_25, $width0_25, $width0_25, $width0_25);

    $query = "SELECT prisoners.prisoner_id, prisoners.name, prisoners.surname, crimes.description, crimes.art FROM prisoners INNER JOIN prisoner_sentence ON prisoners.prisoner_id=prisoner_sentence.prisoner_id INNER JOIN crimes ON crimes.crime_id = prisoner_sentence.crime_id WHERE prisoners.in_prison = '1' AND prisoner_sentence.release_date IS NULL";
    $result_from_database = mysqli_query($dbconn, $query);

    while ($danex = mysqli_fetch_array($result_from_database)) {

        tableLabelFor4($pdf, $danex['prisoner_id'], $danex['name'] . ' ' . $danex['surname'], $danex['description'], $danex['art'], $width0_25, $width0_25, $width0_25, $width0_25);
    }

    $pdf->Ln();
    $pdf->Ln();

    //generowanie pdf
    $pdf->Output('raport.pdf', 'I'); //d-download, i-inline (odczyt)
    


?>