<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);
    $event_name = $data['event_name'];
    $event_start_date = $data['event_start_date'];
    $event_end_date = $data['event_end_date'];
    $event_id = $data['event_id'];
   
    // Sprawdź, czy dane istnieją w zapytaniu POST
    
        // Przypisanie wartości z zapytania POST do zmiennych w PHP
        //$event_name = $_POST['event_name'];
        //$event_start_date = date("Y-m-d", strtotime($_POST['event_start_date']));
        //$event_end_date = date("Y-m-d", strtotime($_POST['event_end_date']));
    try {
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
 
        $insert_query = "INSERT INTO calendar_event_master (event_name, event_start_date, event_end_date, event_id) VALUES ('$event_name','$event_start_date','$event_end_date', '$event_id')"; 

        //$insert_query = "INSERT INTO calendar_event_master VALUES ('$event_name', '$event_start_date', '$event_end_date')";
        //$insert_query = "INSERT INTO `calendar_event_master` VALUES ('hfhf', '2022-12-22', '2022-12-23')";

        $result = mysqli_query($dbconn, $insert_query);

        if ($result) {
            // Zapytanie SQL zakończone sukcesem
            echo json_encode(["status" => true, "msg" => "Event saved successfully"]);
        } else {
            // Błąd w zapytaniu SQL
            echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
        }
    } catch (Exception $e){
        echo json_encode(["status" => false, "msg" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Some data is missing"]);
}

?>
