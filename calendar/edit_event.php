<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['eventName']) && isset($data['date']) && isset($data['end']) && isset($data['visitor']) && isset($data['prisoner']) && isset($data['color']) && isset($data['eventId'])) {

        $eventName = $data['eventName'];
        $date = $data['date'];
        $end = $data['end'];
        $visitors = $data['visitor'];
        $prisoner = $data['prisoner'];
        $color = $data['color'];
        $eventId = $data['eventIdd'];
   
        // Sprawdź, czy dane istnieją w zapytaniu POST
        try {

            $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

            $insert_query = "UPDATE calendar_event_master SET event_name = '$eventName', event_start = '$date', visitors = '$visitors', prisoner = '$prisoner', event_end = '$end', color = '$color' WHERE event_id = $eventId"; 
            $result = mysqli_query($dbconn, $insert_query);

            if ($result) {
                // Zapytanie SQL zakończone sukcesem
                echo json_encode(["status" => true, "msg" => "Wydarzenie zostało pomyślnie zaktualizowane"]);
            } else {
                // Błąd w zapytaniu SQL
                echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
            }
        } catch (Exception $e){
            echo json_encode(["status" => false, "msg" => "Error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => false]);
        //echo json_encode(["status" => false, "msg" => "Some data is missing"]);
    }
}
?>
