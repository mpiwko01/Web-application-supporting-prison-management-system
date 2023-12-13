<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['prisonerId']) && !empty($_POST['prisonerId'])) {

        $prisonerID = $_POST['prisonerId'];

        $date = new DateTime();
        $format = 'Y-m-d'; 
        $curDate = $date->format($format);

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        //update prisoners - in_prison = 0
        $query_prisoners = "UPDATE `prisoners` SET `in_prison` = '0' WHERE `prisoner_id`= '$prisonerID'";
        $result_prisoners = mysqli_query($dbconn, $query_prisoners);

        //upadate cell_history - to_date = release_date
        $query_cell_history = "UPDATE `cell_history` SET `to_date` = '$curDate' WHERE `prisoner_id`= '$prisonerID' AND `to_date` IS NULL";
        $result_cell_history = mysqli_query($dbconn, $query_cell_history);

        //gdy mija data konca wyroku wiezien automatycznie jest usuwany z bazy, skrypt updateDatabase.php wykonuj sie w momencie załadowania apliakacji

        //tutaj tylko gdy usuwamy wieznia ręcznie wczesniej

        $query_prisoner_sentence = "UPDATE `prisoner_sentence` SET `release_date` = '$curDate' WHERE `prisoner_id`= '$prisonerID'";
        $result_prisoner_sentence = mysqli_query($dbconn, $query_prisoner_sentence);

        if ($result_prisoners && $result_cell_history && $result_prisoner_sentence) echo "Więzień został usunięty.";
    }  
}

header('Content-Type: application/json');
//echo json_encode($response);
?>