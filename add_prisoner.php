<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['search']) && isset($_POST['date'])) {



            $searchValue = $_POST['search'];
            $selectedDate = $_POST['date'];
            $selectedCell = $_POST['cell'];
            //$date = $POST['start_date'];

            $searchValueParts = explode(', ', $searchValue);
            $name = $searchValueParts[0]; // Pierwsza część

            $prisoner_id = $searchValueParts[1]; // Druga część

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

                    $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$selectedCell', '$selectedDate', NULL)"; 
        
                    $result = mysqli_query($dbconn,$query);

                    echo "success";


                    //$_SESSION['prisoner_add_try'] = true;

                    //$_SESSION['add_com'] = '<span>Dodano więźnia do celi.</span>';
        
                    //header("Location: map.php");
                }
                else {
                    $_SESSION['add_com'] = '<span>Więzień już ma przypisaną celę.</span>';

                    $_SESSION['prisoner_add_try'] = true;
        
                    header("Location: map.php");
                }


            }
            else {
                //$_SESSION['add_com'] = '<span>Więzień już ma przypisaną celę.</span>';
        
                header("Location: map.php");

            }

            
    

        }
    }
    ?>