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

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        // Pobranie daty końcowej wyroku wybranego więźnia
        $query = "SELECT to_date FROM prisoner_sentence WHERE prisoner_id = $prisoner ORDER BY to_date DESC LIMIT 1;";
        $result1 = mysqli_query($dbconn, $query);

        $monthQuery = "SELECT event_start, event_end FROM calendar_events WHERE MONTH(event_start) = MONTH('$date') AND YEAR(event_start) = YEAR('$date') AND prisoner_id = $prisoner AND type != 'Przepustka' AND event_id != $eventId";
        $monthResult = mysqli_query($dbconn, $monthQuery);
        $usedHours = 0;
        if ($monthResult->num_rows > 0) {
            while ($row = $monthResult->fetch_assoc()) {
                $date1 = new DateTime($row['event_start']);
                $date2 = new DateTime($row['event_end']);
                $diff = $date2->diff($date1);
                $hours = $diff->h;
                $usedHours = $usedHours + $hours; 
            }
        }

        if ($result1){
            $row = mysqli_fetch_assoc($result1);

            //Sprawdzenie czy długość spotkania mieści się w limicie miesięcznym
            if($end <= 3-$usedHours){
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
            else echo json_encode(["status" => false, "msg" => "Spotkanie nie może tyle trwać. Pozostałe maksymalna długość spotkania tego więźnia w tym miesiącu wynosi " . (3-$usedHours) . "h"]);
        }
    } else {
        echo json_encode(["status" => false]);
        //echo json_encode(["status" => false, "msg" => "Some data is missing"]);
    }
}
?>
