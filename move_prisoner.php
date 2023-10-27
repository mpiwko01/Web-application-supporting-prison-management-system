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

            $query_update = "UPDATE `cell_history` SET `to_date`='$selectedDate' WHERE `prisoner_id`='$prisoner_id' AND `to_date` IS NULL";

            $query = "INSERT INTO `cell_history` VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)";

            $result_update = mysqli_query($dbconn, $query_update);
            $result = mysqli_query($dbconn, $query);
                
            echo "success";
        }
    }
    ?>