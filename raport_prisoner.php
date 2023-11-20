<?php

include 'logowanie.php';
require('fpdf/fpdf.php'); 
require('tfpdf/tfpdf.php');

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$prisonerId = $_GET['id']; 

//$query = "SELECT prisoners.name, prisoners.surname, prisoners.birth_date, prisoners.street, prisoners.house_number, prisoners.city, prisoners.zip_code, prisoners.is_reoffender, prisoners.in_prison, crimes.description, crimes.art, prisoner_sentence.from_date, prisoner_sentence.to_date FROM prisoners INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN crimes ON crimes.crime_id = prisoner_sentence.crime_id WHERE prisoners.prisoner_id = '$prisonerId' AND prisoner_sentence.release_date IS NULL";

//czy wiezien jest obecnie w wiezieniu
function inPrison($dbconn, $prisonerId) {
    $query = "SELECT in_prison FROM prisoners WHERE `prisoner_id` = '$prisonerId'";
    $result = mysqli_query($dbconn, $query); //czy obecnie w wiezieniu 
    if($result) {
        $row = mysqli_fetch_assoc($result);
        $inPrison = $row['in_prison'];
        if ($inPrison == 1) return true;
        else return false;
    }
};

function prisoner($dbconn, $prisonerId) {
    $query = "SELECT * FROM prisoners WHERE `prisoner_id` = '$prisonerId'";

    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        return array(
            'name' => $row['name'],
            'surname' => $row['surname'],
            'birthDate' => $row['birth_date'],
            'street' => $row['street'],
            'houseNumber' => $row['house_number'],
            'city' => $row['city'],
            'zipCode' => $row['zip_code']
        );
    }

}


//czy jest recydywista
function isReoffender ($dbconn, $prisonerId) {

    $query = "SELECT is_reoffender FROM prisoners WHERE `prisoner_id`='$prisonerId'"; //czy recydywista

    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $isReoffender = $row['is_reoffender'];
        if ($isReoffender == 1) return true;
        else return false;
    }
}


//obecny wyrok osadzonego
function currentSentence($dbconn, $prisonerId) {
    $query = "SELECT prisoner_sentence.from_date, prisoner_sentence.to_date, crimes.description, crimes.art FROM prisoner_sentence INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id WHERE prisoner_sentence.prisoner_id = '$prisonerId' AND prisoner_sentence.release_date IS NULL";

    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        return array(
            'description' => $row['description'],
            'art' => $row['art'],
            'fromDate' => $row['from_date'],
            'toDate' => $row['to_date']
        );
    }
}


//skonczone wyroki bez obecnego (jesli jest)
function completedSentences($dbconn, $prisonerId) {

    $query = "SELECT prisoner_sentence.from_date, prisoner_sentence.to_date, prisoner_sentence.release_date, crimes.description, crimes.art INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id WHERE prisoner_sentence.prisoner_id = '$prisonerId' AND prisoner_sentence.release_date IS NOT NULL";

    $result = mysqli_query($dbconn, $query);

    $sentencesCompleted = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $sentence = array(
            'crime' => $row['crime'],
            'description' => $row['description'],
            'art' => $row['art'],
            'fromDate' => $row['from_date'],
            'toDate' => $row['to_date'],
            'releaseDate' => $row['release_date']
        );
        $sentencesCompleted[] = $sentence;
    }
    return $sentencesCompleted;
}


//czy przypisany do celi
function inCell($dbconn, $prisonerId) {

    $query = "SELECT COUNT(*) AS count FROM cell_history WHERE `prisoner_id`='$prisonerId' AND `to_date` IS NULL";

    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        if ($count != 0) return true;
        else return false;
    }
}

//obecna cela
function currentCell($dbconn, $prisonerId) {

    $query = "SELECT cell_nr FROM cell_history WHERE `prisoner_id` = '$prisoner_id' AND `to_date` IS NULL";
    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        return array(
            'cellNr' => $row['cell_nr'],
            'fromDate' => $row['from_date'],
            'toDate' => $row['to_date']
        );
    }    
}

//czy mial poprzednie cele
function inPreviousCells($dbconn, $prisonerId) {

    $query = "SELECT COUNT(*) AS count FROM cell_history WHERE `prisoner_id`='$prisonerId' AND `to_date` IS NOT NULL";

    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        if ($count != 0) return true;
        else return false;
    }
}

//jakie poprzednie cele mial
function previousCells($dbconn, $prisonerId) {

    $query = "SELECT cell_nr, from_date, to_date FROM cell_history WHERE `prisoner_id` = '$prisonerId' AND to_date IS NOT NULL";

    $result = mysqli_query($dbconn, $query);

    $previousCells = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $cell = array(
            'cellNr' => $row['cell_nr'],
            'fromDate' => $row['from_date'],
            'toDate' => $row['to_date']
        );
        $previousCells[] = $cell;
    }
    return $previousCells;
}

$prisoner = prisoner($dbconn, $prisonerId);

$name = $prisoner['name'];
$surname = $prisoner['surname'];
$birthDate = $prisoner['birthDate'];
$street = $prisoner['street'];
$houseNumber = $prisoner['houseNumber'];
$city = $prisoner['city'];
$zipCode = $prisoner['zipCode'];

if(inPrison($dbconn, $prisonerId)) { //jest w wiezieniu
    
    $currentSentence = currentSentence($dbconn, $prisonerId);

    $descriptionCurrent = $currentSentence['description'];
    $artCurrent = $currentSentence['art'];
    $fromDateCurrent = $currentSentence['fromDate'];
    $toDateCurrent = $currentSentence['toDate'];

    /*
    if(isReoffender($dbconn, $prisonerId)) { //jest recydywista
        
        $completedSentences = completedSentences($dbconn, $prisonerId);

        foreach ($completedSentences as $sentence) {
            $crime = $sentence['crime'];
            $description = $sentence['description'];
            $art = $sentence['art'];
            $fromDate = $sentence['fromDate'];
            $toDate = $sentence['toDate'];
            $releaseDate = $sentence['releaseDate'];
        }

        if(inCell($dbconn, $prisonerId)) { //ma obecna cele
            $currentCell = currentCell($dbconn, $prisonerId);

            $cellNrCurrent = $currentCell['cellNr'];
            $fromDateCurrent = $currentCell['fromDate'];
            $toDateCurrent = $currentCell['toDate'];

            if(inPreviousCells($dbconn, $prisonerId)) {

                $previousCells = previousCells($dbconn, $prisonerId);

                foreach ($previousCells as $cell) {
                    $cellNr= $cell['cellNr'];
                    $fromDate = $cell['fromDate'];
                    $toDate = $cell['toDate'];  
                }

            }
            else { //WYPISZ: BRAK POPRZENDICH CEL
            }

        }
        else { //nie ma obecnej celi ale jest recydywusta wiec moze miec poprzednie
            if(inPreviousCells($dbconn, $prisonerId)) {

                $previousCells = previousCells($dbconn, $prisonerId);

                foreach ($previousCells as $cell) {
                    $cellNr= $cell['cellNr'];
                    $fromDate = $cell['fromDate'];
                    $toDate = $cell['toDate'];  
                }

            }
            else { //wypisz brak poprzednich cel 

            }



        }

    }
    else { //nie jest recydywista
        if(inCell($dbconn, $prisonerId)) { //ma obecna cele

            $currentCell = currentCell($dbconn, $prisonerId);

            $cellNrCurrent = $currentCell['cellNr'];
            $fromDateCurrent = $currentCell['fromDate'];
            $toDateCurrent = $currentCell['toDate'];

            if(inPreviousCells($dbconn, $prisonerId)) {

                $previousCells = previousCells($dbconn, $prisonerId);

                foreach ($previousCells as $cell) {
                    $cellNr= $cell['cellNr'];
                    $fromDate = $cell['fromDate'];
                    $toDate = $cell['toDate'];  
                }
            }
        }
        else { //WYPISZ: OBECNA CELA NIE PRZYPISANP

        }

    }*/
}/*
else { //nie ma w wiezieniu
    
    $completedSentences = completedSentences($dbconn, $prisonerId);

    foreach ($completedSentences as $sentence) {
        $crime = $sentence['crime'];
        $description = $sentence['description'];
        $art = $sentence['art'];
        $fromDate = $sentence['fromDate'];
        $toDate = $sentence['toDate'];
        $releaseDate = $sentence['releaseDate'];
    }

    $previousCells = previousCells($dbconn, $prisonerId);

    foreach ($previousCells as $cell) {
        $cellNr= $cell['cellNr'];
        $fromDate = $cell['fromDate'];
        $toDate = $cell['toDate'];  
    }

}*/
  

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
if (inPrison($dbconn, $prisonerId)) {
    $pdf->Cell(70,8, "Obecny wyrok:", 0,1);
    $pdf->Cell(70,8, "Czyn zabroniony:". ' '.$descriptionCurrent, 0,0);
    $pdf->Cell(70,8, "Art.". ' '.$artCurrent . " kk", 0,1);
    $pdf->Cell(70,8, "Data początkowa:". ' '.$fromDateCurrent, 0,0);
    $pdf->Cell(70,8, "Data końcowa:". ' '.$toDateCurrent, 0,0);
    $pdf->Ln();
    if(isReoffender($dbconn, $prisonerId)) $pdf->Cell(70,8, "Czy recydywista: TAK", 0,1);
    else $pdf->Cell(70,8, "Czy recydywista: NIE", 0,1);
    
}
else {
    $pdf->Cell(70,8, "Skończone wyroki:", 0,1);

}




$filename = 'raport_' . $prisonerId . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"'); 

$pdf->Output('I');


?>
