<?php

session_start();

include 'conditions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['search1']) && !empty($_POST['search1']) && isset($_POST['date1']) && !empty($_POST['date1'])) {

        $searchValue = $_POST['search1'];
        $selectedDate = $_POST['date1'];
        $selectedCell = $_POST['cell1'];
        $_SESSION['selectedCell'] = $selectedCell;

        $searchValueParts = explode(', ', $searchValue);
        $name = $searchValueParts[0]; 

        $prisoner_id = $searchValueParts[1]; 

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $count = prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate);
        $sex = prisonerSex($dbconn, $prisoner_id, $selectedCell, $selectedDate);
        $reoffender = prisonerReoffender($dbconn, $prisoner_id, $selectedCell, $selectedDate);

        $suggestion = false;

        if ($count == 2 || $sex == 1 || $sex == 2 || $reoffender != 0 || !prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) || !crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate) || !FloorCheck($dbconn, $prisoner_id,$selectedCell)) {
            echo "Więzień $name nie może zostać przeniesiony do celi nr $selectedCell, ponieważ:<br><br>";
            $suggestion = true;
        }
        
        if ($count == 2) echo "- osiągnięto w niej limit miejsc<br>";
        
        if ($sex == 1) echo "- w wybranej celi znajdują się mężczyźni<br>";
        else if ($sex == 2) echo "- w wybranej celi znajdują się kobiety<br>";

        if ($reoffender == 1) echo "- wybrana cela przeznaczona jest dla recydywistów<br>";
        else if ($reoffender == 2) echo "- wybrana cela nie jest przeznaczona jest dla recydywistów<br>";
    
        if (!prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "- wiek więźnia jest niezgodny z wiekiem osadzonych w wybranej celi<br>";  
       
        if (!presentCell($dbconn, $prisoner_id, $selectedCell)) echo "Więzień już znajduje się w podanej celi.<br>";

        if (!crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "- więzień ma inną wagę przestępstwa niż osadzeni w wybranej celi<br>";

        if (!FloorCheck($dbconn, $prisoner_id,$selectedCell)) echo "- wybrane piętro nie jest przeznaczone dla danej płci.<br><br>";

        if (!correctDate($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "Nieprawidłowa data.";

        if ($suggestion) {
            $availableCells = suggestion($dbconn, $prisoner_id, $selectedDate);
            echo "<br><br>Dostępne cele: ";
            echo implode(", ", $availableCells);
        }

        //gdy wszytsko dobrze
        if ($count != 2 && $sex == 0 && $reoffender == 0 &&  prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) && presentCell($dbconn, $prisoner_id, $selectedCell) && correctDate($dbconn, $prisoner_id, $selectedCell, $selectedDate) && crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate) && FloorCheck($dbconn, $prisoner_id,$selectedCell)) {

            $query_update = "UPDATE cell_history SET `to_date`='$selectedDate' WHERE `prisoner_id`='$prisoner_id' AND to_date IS NULL";

            $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";

            $result_update = mysqli_query($dbconn, $query_update);

            $result = mysqli_query($dbconn, $query);

            echo "Więzień został przeniesiony do celi.";
        }
    }
    else echo "Wypełnij wszystkie pola!";
}
?>