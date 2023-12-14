<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['eventType']) && isset($data['date']) && isset($data['end']) && isset($data['visitor']) && isset($data['prisoner']) && isset($data['eventId'])) {

        $eventType = $data['eventType'];
        $date = $data['date'];
        $end = $data['end'];
        $visitor = $data['visitor'];
        $prisoner = $data['prisoner'];
        $eventId = $data['eventId'];

        // Pobranie daty końcowej wyroku wybranego więźnia
        $query = "SELECT to_date FROM prisoner_sentence WHERE prisoner_id = $prisoner ORDER BY to_date DESC LIMIT 1;";
        $result1 = mysqli_query($dbconn, $query);

        if ($result1){
            $row = mysqli_fetch_assoc($result1);

            //Sprawdzenie czy data końcowa wydarzenia jest po dacie początkowej
            if(strtotime($date) < strtotime($end)){
                //Sprawdzenie czy termin eventu mieści się w okresie pobytu w więzieniu danego więźnia
                if(strtotime($row['to_date']) > strtotime($end)){ 
                    $insert_query = "UPDATE calendar_events SET event_id = '$eventId', prisoner_id = '$prisoner', visitor = '$visitor', event_start = '$date', event_end = '$end', type = '$eventType' WHERE event_id = $eventId";
                    $result = mysqli_query($dbconn, $insert_query);
                    if ($result) {
                        // Zapytanie SQL zakończone sukcesem
                        echo json_encode(["status" => true, "msg" => "Event saved successfully", "event_id" => $eventId]);
                    } else {
                        // Błąd w zapytaniu SQL
                        echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
                    }
                } else echo json_encode(["status" => false, "msg" => "Więzień kończy swój wyrok: " . $row['to_date'] . ". Zdarzenia mogą się odbywać tylko przed tym dniem."]);
            }
            else echo json_encode(["status" => false, "msg" => "Godzina zakończenia spotkania nie może być wcześniejsza niż początkowa."]);
        }
    } else {
        //echo json_encode(["status" => false]);
        echo json_encode(["status" => false, "msg" => "Some data is missing"]);
    }
}
?>
