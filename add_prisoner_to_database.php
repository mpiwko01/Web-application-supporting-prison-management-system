<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['surname']) && !empty($_POST['surname']) && isset($_POST['sex']) && !empty($_POST['sex']) && isset($_POST['birthDate']) && !empty($_POST['birthDate']) && isset($_POST['street']) && !empty($_POST['street'])&& isset($_POST['houseNumber']) && !empty($_POST['houseNumber'])&& isset($_POST['city']) && !empty($_POST['city']) && isset($_POST['zipCode']) && !empty($_POST['zipCode']) && isset($_POST['startDate']) && !empty($_POST['startDate']) && isset($_POST['endDate']) && !empty($_POST['endDate']) && isset($_POST['crime']) && !empty($_POST['crime'])) {

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $sex = $_POST['sex'];
        $birthDate = $_POST['birthDate'];
        $street = $_POST['street'];
        $houseNumber = $_POST['houseNumber'];
        $zipCode = $_POST['zipCode'];
        $city = $_POST['city'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $crime = $_POST['crime'];

        $inPrison = 1;
        $isReoffender = 0;

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        // Zapytanie o ostatnie ID więźnia
        $query_prisoner_id = "SELECT MAX(prisoner_id) AS last_prisoner_id FROM prisoners";
        $result_prisoner_id = mysqli_query($dbconn, $query_prisoner_id);

        if ($result_prisoner_id && $result_prisoner_id->num_rows > 0) {
            $row = $result_prisoner_id->fetch_assoc();
            $lastPrisonerID = $row["last_prisoner_id"];
        }
        else {
            $lastPrisonerID = 0;
        }
        $prisonerID = $lastPrisonerID + 1;

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

        // Wstawienie więźnia do tabeli prisoners
        $query_prisoners = "INSERT INTO prisoners VALUES ('$prisonerID', '$name', '$surname', '$sex', '$birthDate', '$street', '$houseNumber', '$city', '$zipCode', '$inPrison', '$isReoffender')";
        $result_prisoners = mysqli_query($dbconn, $query_prisoners);

        if ($result_prisoners) {
            $query_prisoner_sentence = "INSERT INTO prisoner_sentence VALUES ('$sentenceID', '$prisonerID', '$crime', '$startDate', '$endDate', NULL)";
            $result_prisoners_sentence = mysqli_query($dbconn, $query_prisoner_sentence);
        
            if ($result_prisoners_sentence) {
                echo "Więzień dodany do bazy.";
            } 
        }
    }  
}

header('Content-Type: application/json');
//echo json_encode($response);
?>