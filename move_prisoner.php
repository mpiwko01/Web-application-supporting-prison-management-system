<?php

session_start();

include 'conditions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['search1']) && isset($_POST['date1'])) {

        $searchValue = $_POST['search1'];
        $selectedDate = $_POST['date1'];
        $selectedCell = $_POST['cell1'];
        $_SESSION['selectedCell'] = $selectedCell;

        $searchValueParts = explode(', ', $searchValue);
        $name = $searchValueParts[0]; 

        $prisoner_id = $searchValueParts[1]; 

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $count = prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate);
        if ($count == 2) {
            echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ osiągnięto w niej limit miejsc.";
        }

        $sex = prisonerSex($dbconn, $prisoner_id, $selectedCell, $selectedDate);
        if ($sex == 1) echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ znajdują się w niej mężczyźni.";
        else if ($sex == 2) echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ znajdują się w niej kobiety.";

        if (!prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "Wiek więźnia jest niezgodny z wiekiem osadzonych w celi.";

        if (!presentCell($dbconn, $prisoner_id, $selectedCell)) echo "Więzień już znajduje się w podanej celi.";

        if (!correctDate($dbconn, $prisoner_id, $selectedCell, $selectedDate)) echo "Nieprawidłowa data.";

        //gdy wszytsko dobrze
        if ($count != 2 && $sex == 0 && prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) && presentCell($dbconn, $prisoner_id, $selectedCell) && correctDate($dbconn, $prisoner_id, $selectedCell, $selectedDate)) {

            $query_update = "UPDATE cell_history SET `to_date`='$selectedDate' WHERE `prisoner_id`='$prisoner_id' AND to_date IS NULL";

            $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";

            $result_update = mysqli_query($dbconn, $query_update);

            $result = mysqli_query($dbconn, $query);

            echo "Więzień $name został przeniesiony do celi nr $selectedCell.";
        }
    }
}
?>