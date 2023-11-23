<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);
    $eventType = $data['eventType'];
    $date = $data['date'];
    $end = $data['end'];
    $visitor = $data['visitor'];
    $prisoner = $data['prisoner'];
   
    // Sprawdź, czy dane istnieją w zapytaniu POST
    try {
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
        $rowcount = mysqli_num_rows(mysqli_query($dbconn,"SELECT * FROM calendar_events"));
        $eventId = 0;
        if($rowcount == 0) $eventId = 1;
        else{
            for ($i=0; $i<$rowcount; $i++){
                $test = $i+1;
                $still = mysqli_query($dbconn, "SELECT COUNT(event_id) AS val FROM calendar_events WHERE event_id = '$test'");
                $number = mysqli_fetch_assoc($still);
                if ($number['val'] == 0){
                    $eventId = $test;
                    break;
                }
            }
            if ($eventId == 0) $eventId = (int)$rowcount + 1;
        }

        $insert_query = "INSERT INTO calendar_events (event_id, prisoner_id, visitor, event_start, event_end, type) VALUES ('$eventId','$prisoner', '$visitor', '$date', '$end', '$eventType')"; 

        $result = mysqli_query($dbconn, $insert_query);

        if ($result) {
            // Zapytanie SQL zakończone sukcesem
            echo json_encode(["status" => true, "msg" => "Event saved successfully", "event_id" => $eventId]);
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
