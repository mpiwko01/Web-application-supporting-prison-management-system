<?php

include 'logowanie.php';
require('fpdf/fpdf.php'); 
require('tfpdf/tfpdf.php');

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$prisonerId = $_GET['id']; 

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

    $query = "SELECT prisoner_sentence.from_date, prisoner_sentence.to_date, prisoner_sentence.release_date, crimes.description, crimes.art FROM prisoner_sentence INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id WHERE prisoner_sentence.prisoner_id = '$prisonerId' AND prisoner_sentence.release_date IS NOT NULL";

    $result = mysqli_query($dbconn, $query);

    $sentencesCompleted = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $sentence = array(
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

    $query = "SELECT cell_nr, from_date, to_date FROM cell_history WHERE `prisoner_id` = '$prisonerId' AND `to_date` IS NULL";
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

//czy ma powiazania
function ifRelations($dbconn, $prisonerId) {

    $query = "SELECT COUNT(*) AS count
    FROM cell_history ch1
    JOIN cell_history ch2 ON ch1.cell_nr = ch2.cell_nr
    AND ch1.prisoner_id <> ch2.prisoner_id
    WHERE (
        (ch1.from_date <= ch2.to_date AND (ch1.to_date IS NULL OR ch1.to_date >= ch2.from_date OR ch1.to_date >= COALESCE(ch2.to_date, '9999-12-31'))) OR
        (ch1.from_date >= ch2.from_date AND (ch2.to_date IS NULL OR ch2.to_date >= ch1.from_date OR COALESCE(ch2.to_date, '9999-12-31') >= ch1.to_date)) OR
        (ch1.from_date <= ch2.from_date AND (ch1.to_date IS NULL OR ch1.to_date >= ch2.from_date) AND (ch2.to_date IS NULL OR ch2.to_date >= ch1.from_date))
    )
    AND (ch1.prisoner_id IN ($prisonerId) OR ch2.prisoner_id IN ($prisonerId))";

    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        if ($count != 0) return true;
        else return false;
    }
}

//powiazania
function relations($dbconn, $prisonerId) {

    $query = "SELECT DISTINCT ch1.prisoner_id AS prisoner1_id, ch2.prisoner_id AS prisoner2_id, ch1.cell_nr, 
    GREATEST(ch1.from_date, ch2.from_date) AS overlapping_from, 
    LEAST(COALESCE(ch1.to_date, '9999-12-31'), COALESCE(ch2.to_date, '9999-12-31')) AS overlapping_to
    FROM cell_history ch1
    JOIN cell_history ch2 ON ch1.cell_nr = ch2.cell_nr
    AND ch1.prisoner_id <> ch2.prisoner_id
    WHERE (
        (ch1.from_date <= ch2.to_date AND (ch1.to_date IS NULL OR ch1.to_date >= ch2.from_date OR ch1.to_date >= COALESCE(ch2.to_date, '9999-12-31'))) OR
        (ch1.from_date >= ch2.from_date AND (ch2.to_date IS NULL OR ch2.to_date >= ch1.from_date OR COALESCE(ch2.to_date, '9999-12-31') >= ch1.to_date)) OR
        (ch1.from_date <= ch2.from_date AND (ch1.to_date IS NULL OR ch1.to_date >= ch2.from_date) AND (ch2.to_date IS NULL OR ch2.to_date >= ch1.from_date))
    )
    AND (ch1.prisoner_id IN ($prisonerId))
    ORDER BY ch1.cell_nr, overlapping_from, prisoner1_id, prisoner2_id;";

    $result = mysqli_query($dbconn, $query);

    $relations = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $to_date = $row["overlapping_to"];
        if($to_date == '9999-12-31') $to_date = NULL;
        $relation = array(
            "prisonerId" => $row["prisoner1_id"], 
            "prisonerId2" => $row["prisoner2_id"],
            "cellNr" => $row["cell_nr"],
            "fromDate" => $row["overlapping_from"],
            "toDate" => $to_date,
        );
        $relations[] = $relation;
    }
    return $relations;
}

//czy sa jakies odwiedziny w kalndarzu
function ifAnyEvents($dbconn, $prisonerId) {

    $query = "SELECT COUNT(*) as count FROM calendar_events WHERE `prisoner_id` = '$prisonerId' AND `type` != 'Przepustka'";
    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        if ($count != 0) return true;
        else return false;
    }
}

//odwiedziny
function events($dbconn, $prisonerId) {

    $query = "SELECT visitor, type, event_start, event_end FROM calendar_events WHERE `prisoner_id` = '$prisonerId' AND `type` != 'Przepustka'";
    $result = mysqli_query($dbconn, $query);

    $events = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $event = array(
            "visitors" => $row["visitor"],
            "eventName" => $row["type"], 
            "eventStart" => $row["event_start"],
            "eventEnd" => $row["event_end"]
        );
        $events[] = $event;
    }
    return $events;
}

//czy ma przepustki
function ifAnyPasses($dbconn, $prisonerId) {

    $query = "SELECT COUNT(*) as count FROM calendar_events WHERE `prisoner_id` = '$prisonerId' AND `type` = 'Przepustka'";
    $result = mysqli_query($dbconn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        if ($count != 0) return true;
        else return false;
    }
}

//przepustki
function passes($dbconn, $prisonerId) {

    $query = "SELECT event_start, event_end FROM calendar_events WHERE `prisoner_id` = '$prisonerId' AND `type` = 'Przepustka'";
    $result = mysqli_query($dbconn, $query);

    $passes = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $pass = array(
            "startPass" => $row["event_start"],
            "endPass" => $row["event_end"]
        );
        $passes[] = $pass;
    }
    return $passes;
}

function getName($dbconn, $prisonerId){

    $query = "SELECT `name`, `surname` FROM  `prisoners` WHERE `prisoner_id` = $prisonerId";
    $result = mysqli_query($dbconn,$query);
    $row = $result->fetch_assoc();
    return $row["name"] . " " . $row["surname"];
}

$prisoner = prisoner($dbconn, $prisonerId);

$name = $prisoner['name'];
$surname = $prisoner['surname'];
$birthDate = $prisoner['birthDate'];
$street = $prisoner['street'];
$houseNumber = $prisoner['houseNumber'];
$city = $prisoner['city'];
$zipCode = $prisoner['zipCode'];

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

$targetDirectory = 'uploads/';
//$image = glob($targetDirectory . $prisonerId . ".*");
//$image = 'img/blank.png'; 
$imagePath = glob($targetDirectory . $prisonerId . ".*");

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

if ($imagePath) {
    $imageExtension = pathinfo($imagePath[0], PATHINFO_EXTENSION);
    if (in_array($imageExtension, ['png', 'jpg', 'jpeg'])) {
        $pdf->Image($imagePath[0], $pdf->GetPageWidth() - 10 - 55, 78, 55, 0, strtoupper($imageExtension), '', '', true, 300, '', false, false, 1, 'R');
    } 
} 

$pdf->SetFont('DejaVu','', 10);
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

if(isReoffender($dbconn, $prisonerId)) $pdf->Cell(70,8, "Czy recydywista: TAK", 0,1);
else $pdf->Cell(70,8, "Czy recydywista: NIE", 0,1);
$pdf->Ln();

$width = ($pdf->GetPageWidth() - 20)/2;

if(inPrison($dbconn, $prisonerId)) { ///jest w wiezieniu

    $currentSentence = currentSentence($dbconn, $prisonerId);

    $descriptionCurrent = $currentSentence['description'];
    $artCurrent = $currentSentence['art'];
    $fromDateCurrent = $currentSentence['fromDate'];
    $toDateCurrent = $currentSentence['toDate'];

    $pdf->Cell(90,8, "Obecny wyrok:", 0,1);
    $pdf->Cell($width,8, "Czyn zabroniony:". ' '.$descriptionCurrent, 0,0);
    $pdf->Cell($width,8, "Art.". ' '.$artCurrent . " kk", 0,1);
    $pdf->Cell($width,8, "Data początkowa:". ' '.$fromDateCurrent, 0,0);
    $pdf->Cell($width,8, "Data końcowa:". ' '.$toDateCurrent, 0,0);
    $pdf->Ln();
    $pdf->Ln();

    if(isReoffender($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Odbyte wyroki:", 0,1);

        $sentencesCompleted = completedSentences($dbconn, $prisonerId);

        foreach ($sentencesCompleted as $sentence) {
            $description = $sentence['description'];
            $art = $sentence['art'];
            $fromDate = $sentence['fromDate'];
            $toDate = $sentence['toDate'];
            $releaseDate = $sentence['releaseDate'];

            $pdf->Cell($width,8, "Czyn zabroniony:". ' '.$description, 0,0);
            $pdf->Cell($width,8, "Art.". ' '.$art . " kk", 0,1);
            $pdf->Cell($width,8, "Data początkowa:". ' '.$fromDate, 0,0);
            $pdf->Cell($width,8, "Data końcowa:". ' '.$toDate, 0,1);
            $pdf->Cell($width,8, "Data wyjścia:". ' '.$releaseDate, 0,0);
            $pdf->Ln();
            $pdf->Ln();
        }
    } 

    if(inCell($dbconn, $prisonerId)) {

        $currentCell = currentCell($dbconn, $prisonerId);

        $cellNrCurrent = $currentCell['cellNr'];
        $fromDateCurrent = $currentCell['fromDate'];
        $toDateCurrent = $currentCell['toDate'];

        $pdf->Cell($width,8, "Obecna cela:". ' '.$cellNrCurrent, 0,1);
        $pdf->Cell($width,8, "Przebywa od:". ' '.$fromDateCurrent, 0,0);
        if($toDateCurrent == NULL) $pdf->Cell($width,8, "Do: obecnie", 0,0);
        $pdf->Ln();
        $pdf->Ln();
    }
    else {

        $pdf->Cell($width,8, "Obecna cela: nie przydzielono", 0,0);
        $pdf->Ln();
        $pdf->Ln();
    }
    
    if(inPreviousCells($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Poprzednie cele:", 0,1);

        $previousCells = previousCells($dbconn, $prisonerId);

        foreach ($previousCells as $cell) {
            $cellNr = $cell['cellNr'];
            $fromDate = $cell['fromDate'];
            $toDate = $cell['toDate'];

            $pdf->Cell($width,8, "Numer celi:". ' '.$cellNr, 0,1);
            $pdf->Cell($width,8, "Przebywał od:". ' '.$fromDate, 0,0);
            $pdf->Cell($width,8, "Do:". ' '.$toDate, 0,0);
            $pdf->Ln();
        }
        $pdf->Ln();
    }

    if(ifRelations($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Powiązania:", 0,1);

        $relations = relations($dbconn, $prisonerId);

        foreach ($relations as $relation) {
            $prisonerName = getName($dbconn, $relation['prisonerId2']);
            $cellNr = $relation['cellNr'];
            $fromDate = $relation['fromDate'];
            $toDate = $relation['toDate'];

            $pdf->Cell($width,8, "Współwięzień:". ' '.$prisonerName, 0,0);
            $pdf->Cell($width,8, "Numer celi:". ' '.$cellNr, 0,1);
            $pdf->Cell($width,8, "Od:". ' '.$fromDate, 0,0);
            if($toDate == NULL) $pdf->Cell($width,8, "Do: obecnie", 0,1);
            else $pdf->Cell($width,8, "Do:". ' '.$toDate, 0,0);
            $pdf->Ln();
        }
        $pdf->Ln();
    }

    if(ifAnyEvents($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Historia odwiedzin:", 0,1);

        $events = events($dbconn, $prisonerId);

        foreach ($events as $event) {
            $visitors = $event['visitors'];
            $eventName = $event['eventName'];
            $eventStart = $event['eventStart'];
            $eventEnd = $event['eventEnd'];

            $pdf->Cell($width,8, "Odwiedzający:". ' '.$visitors, 0,0);
            $pdf->Cell($width,8, "Rodzaj odwiedzin:". ' '.$eventName, 0,1);
            $pdf->Cell($width,8, "Od:". ' '.$eventStart, 0,0);
            $pdf->Cell($width,8, "Do:".$eventEnd, 0,0);
            $pdf->Ln();   
        }
        $pdf->Ln();
    }

    if(ifAnyPasses($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Przepustki:", 0,1);

        $passes = passes($dbconn, $prisonerId);

        foreach ($passes as $pass) {
            $startPass = $pass['startPass'];
            $endPass = $pass['endPass'];
        
            $pdf->Cell($width,8, "Od:". ' '.$startPass, 0,0);
            $pdf->Cell($width,8, "Do:".$endPass, 0,0);
            $pdf->Ln();   
        }
        $pdf->Ln();
    }
}

else { //opuscil wiezienie

    $pdf->Cell($width,8, "Odbyte wyroki:", 0,1);

    $sentencesCompleted = completedSentences($dbconn, $prisonerId);

    foreach ($sentencesCompleted as $sentence) {
        $description = $sentence['description'];
        $art = $sentence['art'];
        $fromDate = $sentence['fromDate'];
        $toDate = $sentence['toDate'];
        $releaseDate = $sentence['releaseDate'];

        $pdf->Cell($width,8, "Czyn zabroniony:". ' '.$description, 0,0);
        $pdf->Cell($width,8, "Art.". ' '.$art . " kk", 0,1);
        $pdf->Cell($width,8, "Data początkowa:". ' '.$fromDate, 0,0);
        $pdf->Cell($width,8, "Data końcowa:". ' '.$toDate, 0,1);
        $pdf->Cell($width,8, "Data wyjścia:". ' '.$releaseDate, 0,0);
        $pdf->Ln();
    }
    $pdf->Ln();

    if(inPreviousCells($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Zajmowane cele:", 0,1);

        $previousCells = previousCells($dbconn, $prisonerId);

        foreach ($previousCells as $cell) {
            $cellNr = $cell['cellNr'];
            $fromDate = $cell['fromDate'];
            $toDate = $cell['toDate'];

            $pdf->Cell($width,8, "Numer celi:". ' '.$cellNr, 0,1);
            $pdf->Cell($width,8, "Przebywał od:". ' '.$fromDate, 0,0);
            $pdf->Cell($width,8, "Do:". ' '.$toDate, 0,1);
        }
    }

    if(ifRelations($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Powiązania:", 0,1);

        $relations = relations($dbconn, $prisonerId);

        foreach ($relations as $relation) {
           
            $prisonerId2 = $relation['prisonerId2'];
            $cellNr = $relation['cellNr'];
            $fromDate = $relation['fromDate'];
            $toDate = $relation['toDate'];

            $pdf->Cell($width,8, "Współwięzień:". ' '.$prisonerId2, 0,0);
            $pdf->Cell($width,8, "Numer celi:". ' '.$cellNr, 0,1);
            $pdf->Cell($width,8, "Od:". ' '.$fromDate, 0,0);
            if($toDate == NULL) $pdf->Cell($width,8, "Do: obecnie", 0,1);
            else $pdf->Cell($width,8, "Do:". ' '.$toDate, 0,1);
            $pdf->Ln(); 
        }
    }

    if(ifAnyEvents($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Historia odwiedzin:", 0,1);

        $events = events($dbconn, $prisonerId);

        foreach ($events as $event) {
            $visitors = $event['visitors'];
            $eventName = $event['eventName'];
            $eventStart = $event['eventStart'];
            $eventEnd = $event['eventEnd'];

            $pdf->Cell($width,8, "Odwiedzający:". ' '.$visitors, 0,0);
            $pdf->Cell($width,8, "Rodzaj odwiedzin:". ' '.$eventName, 0,1);
            $pdf->Cell($width,8, "Od:". ' '.$eventStart, 0,0);
            $pdf->Cell($width,8, "Do:".$eventEnd, 0,0);
            $pdf->Ln();   
        }
        $pdf->Ln();
    }

    if(ifAnyPasses($dbconn, $prisonerId)) {

        $pdf->Cell($width,8, "Przepustki:", 0,1);

        $passes = passes($dbconn, $prisonerId);

        foreach ($passes as $pass) {
            $startPass = $pass['startPass'];
            $endPass = $pass['endPass'];
        
            $pdf->Cell($width,8, "Od:". ' '.$startPass, 0,0);
            $pdf->Cell($width,8, "Do:".$endPass, 0,0);
            $pdf->Ln();   
        }
        $pdf->Ln();
    }
}

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->Cell(2*$width, 5, "________________________________", 0, 1, 'R');
$pdf->SetFont('DejaVu','', 8);
$pdf->Cell(2*$width, 5, "pieczęć                         ", 0, 0, 'R');

$filename = 'raport_' . $prisonerId . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"'); 

$pdf->Output('I');

?>
