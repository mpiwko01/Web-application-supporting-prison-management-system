<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['startDate']) && !empty($_POST['startDate']) && isset($_POST['endDate']) && !empty($_POST['endDate']) && isset($_POST['crime']) && !empty($_POST['crime']) && isset($_POST['prisonerId']) && !empty($_POST['prisonerId'])) {

        $prisonerID = $_POST['prisonerId'];
        $startDate = $_POST['startDate'];
        $endDate= $_POST['endDate'];
        $crime = $_POST['crime'];

        $date = new DateTime();
        $format = 'Y-m-d'; 
        $curDate = $date->format($format);

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        //update prisoners - in_prison = 1, is_reoffender = 1;
        $query_prisoners = "UPDATE `prisoners` SET `in_prison` = '1', `is_reoffender` = '1' WHERE `prisoner_id`= '$prisonerID'";
        $result_prisoners = mysqli_query($dbconn, $query_prisoners);

        // Zapytanie o ostatnie ID wyroku
        $query_sentence_id = "SELECT MAX(sentence_id) AS last_sentence_id FROM prisoner_sentence";
        $result_sentence_id = mysqli_query($dbconn, $query_sentence_id);

        if ($result_sentence_id && $result_sentence_id->num_rows > 0) {
            $row = $result_sentence_id->fetch_assoc();
            $lastSentenceID = $row["last_sentence_id"];
        }
        else {
            $lastSentenceID = 0;
        }
        $sentenceID = $lastSentenceID + 1;

        //insert prisoner_sentence
        $query_prisoner_sentence = "INSERT INTO prisoner_sentence VALUES ('$sentenceID', '$prisonerID', '$crime', '$startDate', '$endDate', NULL)";
        $result_prisoner_sentence = mysqli_query($dbconn, $query_prisoner_sentence);

        if ($result_prisoners && $result_prisoner_sentence) echo "Wyrok został dodany.";
    }  
}

header('Content-Type: application/json');
//echo json_encode($response);
?>