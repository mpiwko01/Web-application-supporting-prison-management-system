<?php

session_start();

if (isset($_POST['prisoner_add'])) {

        $prisoner_id = $_POST['prisoner_add_id'];
        $cell_number = $_POST['prisoner_add_cell_number'];

        //LOGOWANIE DO PHPMYADMIN
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        //$query = "SELECT * FROM cells_history WHERE 'prisoner_id' = '$prisoner_id";

        //$result = mysqli_query($dbconn,$query);

        $query_counter = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `prisoner_id` = '$prisoner_id'";

        $result_query_counter = mysqli_query($dbconn, $query_counter);

        if ($result_query_counter) {
            $row_counter = mysqli_fetch_assoc($result_query_counter);
            $all_query = $row_counter['query_counter'];

            if ($all_query == 0) {

                $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$cell_number', '2022-12-10', '2022-12-15')"; //daty do poprawy, na razie na sztywno wstawione
    
                $result = mysqli_query($dbconn,$query);

                $_SESSION['prisoner_add_try'] = true;

                $_SESSION['add_com'] = '<span>Dodano więźnia do celi.</span>';
    
                header("Location: map.php");
            }
            else {
                $_SESSION['add_com'] = '<span>Więzień już ma przypisaną celę.</span>';

                $_SESSION['prisoner_add_try'] = true;
    
                header("Location: map.php");
            }


        }
        else {
            $_SESSION['add_com'] = '<span>Więzień już ma przypisaną celę.</span>';
    
            header("Location: map.php");

        }

        
 

    }
?>