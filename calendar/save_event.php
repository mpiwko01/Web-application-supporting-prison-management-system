<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Sprawdź, czy dane istnieją w zapytaniu POST
    if (isset($data['eventType']) && isset($data['date']) && isset($data['end']) && isset($data['visitor']) && isset($data['prisoner'])){
        $eventType = $data['eventType'];
        $date = $data['date'];
        $end = $data['end'];
        $visitor = $data['visitor'];
        if ($visitor == "BRAK") $visitor = NULL;
        $prisoner = $data['prisoner'];

        $endDate = strtotime($date . " " . $end . "hours");
        $endDate = date("Y-m-d\TH:i:s", $endDate);

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        // Wyliczenie ID eventu
        $rowcount = mysqli_num_rows(mysqli_query($dbconn,"SELECT * FROM calendar_events")); // Pobranie liczby eventów w tabeli
        $eventId = 0;
        if($rowcount == 0) $eventId = 1; // Jeśli w tabeli nie ma żadnego eventu, przypisywane jest ID = 1
        else{
            for ($i=0; $i<$rowcount; $i++){ // Sprawdzenie czy istnieje jakieś niewykorzystane ID przed największym ID w tabeli
                $test = $i+1;
                $still = mysqli_query($dbconn, "SELECT COUNT(event_id) AS val FROM calendar_events WHERE event_id = '$test'");
                $number = mysqli_fetch_assoc($still);
                if ($number['val'] == 0){ // Jeśli istnieje niewykorzystane ID to jest ono przypisywane dodawanemu eventowi
                    $eventId = $test;
                    break;
                }
            }
            if ($eventId == 0) $eventId = (int)$rowcount + 1; // Jeśli nie ma żadnych niewykorzystanych ID to przypisywane jest ID o 1 większe od największego
        }

        // Pobranie daty końcowej wyroku wybranego więźnia
        $query = "SELECT to_date FROM prisoner_sentence WHERE prisoner_id = $prisoner ORDER BY to_date DESC LIMIT 1;";
        $result1 = mysqli_query($dbconn, $query);

        $monthQuery = "SELECT event_start, event_end FROM calendar_events WHERE MONTH(event_start) = MONTH('$date') AND YEAR(event_start) = YEAR('$date') AND prisoner_id = $prisoner AND type != 'Przepustka'";
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
                if(strtotime($row['to_date']) > strtotime($endDate)){ 
                    $insert_query = "INSERT INTO calendar_events (event_id, prisoner_id, visitor, event_start, event_end, type) VALUES ('$eventId','$prisoner', '$visitor', '$date', '$endDate', '$eventType')"; 
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
            else echo json_encode(["status" => false, "msg" => "Spotkanie nie może tyle trwać. Pozostałe godziny spotkań więźnia w tym miesiącu: " . (3-$usedHours) . "h"]);
        }
    } else {
        echo json_encode(["status" => false, "msg" => "Brakuje niektórych danych."]);
    }
}
?>
