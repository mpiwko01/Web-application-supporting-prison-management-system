<?php
session_start();

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

        $cell_counter = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `cell_nr` = '$selectedCell' AND `to_date` IS NULL"; //obecna liczba więźniów w wybranej celi
        $result_cell_counter = mysqli_query($dbconn, $cell_counter);
        $cell_sex = "SELECT sex FROM prisoners WHERE `prisoner_id` IN (SELECT prisoner_id FROM cell_history WHERE `cell_nr` = $selectedCell AND `to_date` is NULL) LIMIT 1"; //płeć więźniów w celi
        $result_cell_sex = mysqli_query($dbconn, $cell_sex);
        $prisoner_sex = "SELECT sex FROM prisoners WHERE $prisoner_id = `prisoner_id`"; //płeć wybranego więźnia
        $result_prisoner_sex = mysqli_query($dbconn, $prisoner_sex);
        $query_counter = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `prisoner_id` = '$prisoner_id' AND `to_date` IS NULL"; //sprawdzam czy wybrany więzień jest obecnie w innej celi
        $result_query_counter = mysqli_query($dbconn, $query_counter);
        
        if($result_query_counter && $result_cell_counter && $result_cell_counter && $result_prisoner_sex){
            $row_counter = mysqli_fetch_assoc($result_query_counter);
            $all_query = $row_counter['query_counter'];
            $row_cell_counter = mysqli_fetch_assoc($result_cell_counter);
            $count = $row_cell_counter['query_counter'];
            $row_prisoner_sex = mysqli_fetch_assoc($result_prisoner_sex);
            $prisoner_sex1 = $row_prisoner_sex['sex'];
            if($all_query == 0) {
                if ($count < 4 && $count > 0) {
                    $row_cell_sex = mysqli_fetch_assoc($result_cell_sex);
                    $cell_sex1 = $row_cell_sex['sex'];
                    if ($cell_sex1 == $prisoner_sex1) {
                        $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";
                        $result = mysqli_query($dbconn, $query);
                        echo "Więzień $name dodany do celi nr $selectedCell.";
                    } else if ($cell_sex1 == "F") { 
                        echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ znajdują się w niej kobiety.";
                    } else {
                        echo "Więzień $name nie może zostać dodana do celi nr $selectedCell, ponieważ znajdują się w niej mężczyźni.";
                    }
                } else if ($count == 0) {
                    $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";
                    $result = mysqli_query($dbconn, $query);
                    echo "Więzień $name dodany do celi nr $selectedCell.";
                } else {
                    echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ osiągnięo w niej limit miejsc.";
                }
            } else {
                    echo "Więzień $name nie może zostać dodany do żadnej celi, ponieważ już znajduje się w więzieniu.";
                }
        } else {
            echo "Wystąpił błąd z wybraniem danych z bazy.";
        }
    } 
}
?>