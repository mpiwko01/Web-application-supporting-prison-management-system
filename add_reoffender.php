<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['startDate']) && !empty($_POST['startDate']) && isset($_POST['endDate']) && !empty($_POST['endDate']) && isset($_POST['crime']) && !empty($_POST['crime']) && isset($_POST['prisonerId']) && !empty($_POST['prisonerId'])) {

        $prisonerID = $_POST['prisonerId'];
        $startDate = $_POST['startDate'];
        $endDate= $_POST['endDate'];
        $crime = $_POST['crime'];

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $query_last_sentence = "SELECT severity, release_date FROM prisoner_sentence INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id WHERE `prisoner_id`= '$prisonerID' AND `sentence_id` = (SELECT MAX(sentence_id) FROM prisoner_sentence WHERE `prisoner_id`= '$prisonerID')";
        $result_last_sentence = mysqli_query($dbconn, $query_last_sentence);

        if($result_last_sentence) {
            $row = $result_last_sentence->fetch_assoc();
            $severity = $row["severity"];
            $releaseDate = $row["release_date"];
        }

        $startDateYear = new DateTime($startDate);
        $format = 'Y'; 
        $startDateYear = $startDateYear->format($format);

        $releaseDateYear = new DateTime($releaseDate);
        $releaseDateYear = $releaseDateYear->format($format);

        $period = $startDateYear->diff($releaseDateYear)->y; 

        $query_severity = "SELECT severity FROM crimes WHERE `crime_id` = '$crime'";
        $result_severity = mysqli_query($dbconn, $query_severity);

        if($result_severity) {
            $row = $result_severity->fetch_assoc();
            $severityCurrent = $row["severity"];
        }

        if($severity == $severityCurrent && abs($age) <= 5) $isReoffender = '1';
        else $isReoffender = '0';

        //update prisoners - in_prison = 1, is_reoffender = 1;
        $query_prisoners = "UPDATE `prisoners` SET `in_prison` = '1', `is_reoffender` = '$isReoffender' WHERE `prisoner_id`= '$prisonerID'";
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