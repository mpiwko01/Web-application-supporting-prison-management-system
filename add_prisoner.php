<?php
session_start();

include 'conditions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search']) && isset($_POST['date'])) {
        $searchValue = $_POST['search'];
        $selectedDate = $_POST['date'];
        $selectedCell = $_POST['cell'];
        $_SESSION['selectedCell'] = $selectedCell;

        $searchValueParts = explode(', ', $searchValue);
        $name = $searchValueParts[0];
        $prisoner_id = $searchValueParts[1];

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $count = prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate);
        if ($count == 2) {
            echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ osiągnięto w niej limit miejsc.";
            $availableCells = suggestCell($dbconn);
            echo "Dostępne cele: ";
            foreach ($availableCells as $cell) {
                echo $cell . " ";
            }
        }

        $sex = prisonerSex($dbconn, $prisoner_id, $selectedCell, $selectedDate);
        if ($sex == 1) {
            echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ znajdują się w niej mężczyźni.";
            $availableCells = suggestSex($dbconn, $prisoner_id);
            echo "Dostępne cele: ";
            foreach ($availableCells as $cell) {
                echo $cell . " ";
            }
        }
        else if ($sex == 2) {
            echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ znajdują się w niej kobiety.";
            $availableCells = suggestSex($dbconn, $prisoner_id);
            echo "Dostępne cele: ";
            foreach ($availableCells as $cell) {
                echo $cell . " ";
            }
        }
    
        if (!prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate)) {
            echo "Wiek więźnia jest niezgodny z wiekiem osadzonych w celi.";
            $availableCells = suggestAge($dbconn, $prisoner_id, $selectedDate);
            echo "Dostępne cele: ";
            foreach ($availableCells as $cell) {
                echo $cell . " ";
            }
        }
        
        if (!presentCell($dbconn, $prisoner_id, $selectedCell)) echo "Więzień już znajduje się w podanej celi.";

        if (!crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate)) {
            echo "Więzień ma inną wagę przestępstwa niż osadzeni w celi.";
            $availableCells = suggestSeverity($dbconn, $prisoner_id);
            echo "Dostępne cele: ";
            foreach ($availableCells as $cell) {
                echo $cell . " ";
            }
        } 

        //gdy wszytsko dobrze
        if ($count != 2 && $sex == 0 && prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) && presentCell($dbconn, $prisoner_id, $selectedCell) && crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate)) {
            $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";
            $result = mysqli_query($dbconn, $query);
            echo "Więzień $name dodany do celi nr $selectedCell.";
        }

    }
}
?>






