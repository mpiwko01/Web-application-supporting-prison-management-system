<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['prisonerId']) && !isset($_POST['prisonerId'])) {

        $prisonerID = $_POST['prisonerId'];

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $prisoners = array();

        $query = "SELECT early_dep FROM `prisoner_sentence` WHERE `prisoner_id` = '$prisonerID'";
        $result = mysqli_query($dbconn, $query);

        if($result) {
            $row = mysqli_fetch_assoc($result);
            if($row) {
                $earlyDep = $row['early_dep'];
                if ($earlyDep == NULL)
                {
                    $query2 = "SELECT to_date from `prisoner_sentence` WHERE `prisoner_id` = '$prisonerID'";
                    $result2 = mysqli_query($dbconn, $query2);
                    if($result2) {
                        $row2 = mysqli_fetch_assoc($result2);
                        if($row2) $depDate = $row2['to_date'];
                    }
                }
                else $depDate = $earlyDep;
            }
        }


    
    }
}
?>






