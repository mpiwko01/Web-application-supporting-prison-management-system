<?php

session_start();

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

            $query_cell = "SELECT * FROM cell_history WHERE `prisoner_id`='$prisoner_id' AND `to_date` IS NULL";

            $result_cell = mysqli_query($dbconn, $query_cell);

            if($result_cell) {
                $row = mysqli_fetch_array($result_cell);
                if ($row) {
                    $currentCell =  $row['cell_nr'];
                    if ($currentCell != $selectedCell) {
                        $query_update = "UPDATE `cell_history` SET `to_date`='$selectedDate' WHERE `prisoner_id`='$prisoner_id' AND `to_date` IS NULL";

                        $query = "INSERT INTO `cell_history` VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";

                        $result_update = mysqli_query($dbconn, $query_update);

                        $result = mysqli_query($dbconn, $query);
                
                        echo "Więzień $name został przeniesiony do celi nr $selectedCell.";
                    }
                    else {
                        echo "Więzień $name nie może zostać przeniesiony do celi nr $selectedCell, ponieważ już się w niej znajduje. Wybierz inną celę.";
                    }
                }
            }

            
        }
    }
    ?>