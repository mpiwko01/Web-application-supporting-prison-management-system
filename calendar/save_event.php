<?php
session_start();

if(isset($_POST['dodaj'])) {
    if (isset($_POST['event_name']) && isset($_POST['event_start_date']) && isset($_POST['event_end_date'])) {
        $event_name = mysqli_real_escape_string($dbconn, $_POST['event_name']);
        $event_start_date = date("Y-m-d", strtotime($_POST['event_start_date'])); 
        $event_end_date = date("Y-m-d", strtotime($_POST['event_end_date'])); 
    
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
    
        $query = "INSERT INTO calendar_event_master (event_name, event_start_date, event_end_date) VALUES ('$event_name', '$event_start_date', '$event_end_date')";
    
        $result = mysqli_query($dbconn, $query);
    
        if ($result) {
            // Zapytanie SQL zakończone sukcesem
            echo json_encode(["status" => true, "msg" => "Event saved successfully"]);
        } else {
            // Błąd w zapytaniu SQL
            echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
        }
    }
}

?>