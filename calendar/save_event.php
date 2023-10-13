<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sprawdź, czy dane istnieją w zapytaniu POST
    if (isset($_POST["event_name"]) && isset($_POST["event_start_date"]) && isset($_POST["event_end_date"])) {
        // Przypisanie wartości z zapytania POST do zmiennych w PHP
        $event_name = $_POST['event_name'];
        $event_start_date = date("Y-m-d", strtotime($_POST['event_start_date']));
        $event_end_date = date("Y-m-d", strtotime($_POST['event_end_date']));

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $insert_query = "insert into `calendar_event_master`(`event_name`,`event_start_date`,`event_end_date`) values ('".$event_name."','".$event_start_date."','".$event_end_date."')"; 

        //$insert_query = "INSERT INTO calendar_event_master VALUES ('$event_name', '$event_start_date', '$event_end_date')";
        //$insert_query = "INSERT INTO `calendar_event_master` VALUES ('hfhf', '2022-12-22', '2022-12-23')";

        $result = mysqli_query($dbconn, $insert_query);

        //$row = mysqli_fetch_array($result);

        if ($result) {
            // Zapytanie SQL zakończone sukcesem
            echo json_encode(["status" => true, "msg" => "Event saved successfully"]);
        }
    } else {
            // Dane nie zostały przesłane poprawnie
            echo json_encode(["status" => false, "msg" => "Some data is missing"]);
        }

    } else {
        // Błąd w zapytaniu SQL
        echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
    }

?>
