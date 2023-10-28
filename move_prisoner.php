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

        $cell_counter = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `cell_nr` = '$selectedCell' AND `to_date` IS NULL"; //obecna liczba więźniów w wybranej celi
        $result_cell_counter = mysqli_query($dbconn, $cell_counter);

        $cell_sex = "SELECT * FROM prisoners WHERE `prisoner_id` IN (SELECT prisoner_id FROM cell_history WHERE `cell_nr` = '$selectedCell' AND `to_date` IS NULL) LIMIT 1"; //płeć więźniów w celi
        $result_cell_sex = mysqli_query($dbconn, $cell_sex);

        $prisoner_sex = "SELECT sex FROM prisoners WHERE `prisoner_id`='$prisoner_id'"; //płeć wybranego więźnia
        $result_prisoner_sex = mysqli_query($dbconn, $prisoner_sex);

        if($result_cell && $result_cell_sex && $result_prisoner_sex && $result_cell_counter) {

            $row = mysqli_fetch_array($result_cell);
            $currentCell =  $row['cell_nr'];
            $currentDate = $row['from_date'];

            $row_cell_counter = mysqli_fetch_assoc($result_cell_counter);
            $count = $row_cell_counter['query_counter'];

            $row_prisoner_sex = mysqli_fetch_assoc($result_prisoner_sex);
            $prisoner_sex1 = $row_prisoner_sex['sex'];

            if ($currentCell != $selectedCell) {
                if ($count == 0) {
                    if(strtotime($currentDate) < strtotime($selectedDate)) {
                        $query_update = "UPDATE `cell_history` SET `to_date`='$selectedDate' WHERE `prisoner_id`='$prisoner_id' AND `to_date` IS NULL";

                        $query = "INSERT INTO `cell_history` VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";

                        $result_update = mysqli_query($dbconn, $query_update);

                        $result = mysqli_query($dbconn, $query);
                    
                        echo "Więzień $name został przeniesiony do celi nr $selectedCell.";
                    }
                    else {
                        echo "Nieprawidłowa data";
                    }   
                }
                else if ($count < 4) {
                    $row_cell_sex = mysqli_fetch_assoc($result_cell_sex);
                    $cell_sex1 = $row_cell_sex['sex'];
                    if ($cell_sex1 == $prisoner_sex1) {
                        if(strtotime($currentDate) < strtotime($selectedDate)) {
                            $query_update = "UPDATE `cell_history` SET `to_date`='$selectedDate' WHERE `prisoner_id`='$prisoner_id' AND `to_date` IS NULL";

                            $query = "INSERT INTO `cell_history` VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";

                            $result_update = mysqli_query($dbconn, $query_update);

                            $result = mysqli_query($dbconn, $query);
                
                            echo "Więzień $name został przeniesiony do celi nr $selectedCell.";

                        }
                        else {
                            echo "Nieprawidłowa data";
                        }  
                    }
                    else if ($cell_sex1 == "F") { 
                        echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ znajdują się w niej kobiety.";
                    }
                    else if ($cell_sex1 == "M") {
                        echo "Więzień $name nie może zostać dodana do celi nr $selectedCell, ponieważ znajdują się w niej mężczyźni.";
                    }
                    }
                }
                else {
                    echo "Więzień $name nie może zostać dodany do celi nr $selectedCell, ponieważ osiągnięo w niej limit miejsc.";
                }    
            }
            else {
                echo "Więzień $name nie może zostać przeniesiony do celi nr $selectedCell, ponieważ już się w niej znajduje. Wybierz inną celę.";      
            }
        }
        else {
            echo "Wystąpił błąd z wybraniem danych z bazy.";
        }          
    }

?>