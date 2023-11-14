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
        $sex = prisonerSex($dbconn, $prisoner_id, $selectedCell, $selectedDate);

        $suggestion = false;

        
        if ($count == 2 || $sex == 1 || $sex == 2 || !prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) || !crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate)) {
            echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ:<br><br>";
            $suggestion = true;
        }

        if ($count == 2) echo "- osiągnięto w niej limit miejsc<br>";
    
        if ($sex == 1) echo "- w wybranej celi znajdują się mężczyźni<br>";
        else if ($sex == 2) echo "- w wybranej celi znajdują się kobiety<br>";
        
        if (!prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "- wiek więźnia jest niezgodny z wiekiem osadzonych w wybranej celi<br>";   
        
        if (!presentCell($dbconn, $prisoner_id, $selectedCell)) echo "Więzień już znajduje się w podanej celi.<br>";

        if (!crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "- więzień ma inną wagę przestępstwa niż osadzeni w wybranej celi<br>";
        
        if ($suggestion) {
            $availableCells = suggestion($dbconn, $prisoner_id, $selectedDate);
            echo "<br><br>Dostępne cele: ";
            echo implode(", ", $availableCells);
        }
        
        //gdy wszytsko dobrze
        if ($count != 2 && $sex == 0 && prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) && presentCell($dbconn, $prisoner_id, $selectedCell) && crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate)) {
            $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";
            $result = mysqli_query($dbconn, $query);
            echo "Więzień $name dodany do celi nr $selectedCell.<br>";
        }
    }
}
?>






