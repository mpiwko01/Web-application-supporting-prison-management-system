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

            $query_counter = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `prisoner_id` = '$prisoner_id'";

            $result_query_counter = mysqli_query($dbconn, $query_counter);

            if ($result_query_counter) {
                $row_counter = mysqli_fetch_assoc($result_query_counter);
                $all_query = $row_counter['query_counter'];

                if ($all_query == 0) {

                    $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)"; 
        
                    $result = mysqli_query($dbconn,$query);

                    //header("Location: map.php");
                    echo "success";
                    
                }
                else {
                    header("Location: map.php");
                }
            }
            else {
                header("Location: map.php");
            }
        }
    }
    ?>