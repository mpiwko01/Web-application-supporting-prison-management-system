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

        // Dodawanie przepustek
        if(is_null($visitor)){
            $daysQuery = "SELECT event_start, event_end FROM calendar_events WHERE YEAR(event_start) = YEAR('$date') AND prisoner_id = $prisoner AND type = 'Przepustka'"; // Zapytanie zwracające daty rozpoczęcia i zakończenia przepustek z roku, na który dodawana jest przepustka
            $lastPassQuery = "SELECT event_end FROM calendar_events WHERE prisoner_id = $prisoner AND type = 'Przepustka' ORDER BY  event_end DESC LIMIT 1"; // Sprawdzenie kiedy zakończyła się ostatnia przepustka przed dodawną (jeśli istnieje taka)
            $daysResult = mysqli_query($dbconn, $daysQuery);
            $lastPassResult = mysqli_query($dbconn, $lastPassQuery);
            $usedDays = 0; // Zmienna przechowująca liczbę zużytych dni z rocznego limitu dni na przepustki
            if ($daysResult->num_rows > 0){
                while ($row = $daysResult->fetch_assoc()) {
                    $date1 = strtotime($row['event_start']);
                    $date2 = strtotime($row['event_end']);
                    $usedDays = $usedDays + abs(floor(($date2-$date1)/86400));
                }
            }
            $monthDifference = 0; // Zmienna przechowująca liczbę dni od ostatniej przepustki
            if ($lastPassResult->num_rows > 0){
                $monthRow = mysqli_fetch_assoc($lastPassResult);
                $date1 = strtotime($monthRow['event_end']);
                $date2 = strtotime($date);
                $monthDifference = $monthDifference + abs(floor(($date2-$date1)/86400)); 
            }
            $passDuration = 0;
            $passStart = strtotime($date);
            $passEnd = strtotime($end);
            $passDuration = $passDuration + abs(floor(($passEnd-$passStart)/86400)); // Zmienna przechowująca długość (w dniach) dodawanej przepustki
            if ($result1){
                $row = mysqli_fetch_assoc($result1);

                //Sprawdzenie czy data końcowa wydarzenia jest po dacie początkowej
                if(strtotime($date) < strtotime($end)){
                    //Sprawdzenie czy od ostatniej przepustki minęły dwa miesiące
                    if($monthDifference > 60){
                        //Sprawdzenie czy długość przepustki mieści się w limicie rocznym
                        if($passDuration <= 14-$usedDays){
                            //Sprawdzenie czy termin przepustki mieści się w okresie pobytu w więzieniu danego więźnia
                            if(strtotime($row['to_date']) > strtotime($end)){ 
                                $insert_query = "INSERT INTO calendar_events (event_id, prisoner_id, visitor, event_start, event_end, type) VALUES ('$eventId','$prisoner', '$visitor', '$date', '$end', '$eventType')"; 
                                $result = mysqli_query($dbconn, $insert_query);
                                if ($result) echo json_encode(["status" => true, "msg" => "Event saved successfully", "event_id" => $eventId]); // Zapytanie SQL zakończone sukcesem
                                else echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]); // Błąd w zapytaniu SQL
                            } else echo json_encode(["status" => false, "msg" => "Więzień kończy swój wyrok: " . $row['to_date'] . ". Zdarzenia mogą się odbywać tylko przed tym dniem."]);
                        } else echo json_encode(["status" => false, "msg" => "Przepustka nie może tyle trwać. Liczba pozostałych dni przepustki więźnia w tym roku: " . (14-$usedDays) . " dni"]);
                    } else echo json_encode(["status" => false, "msg" => "Kolejna przepustka może zostać dodana najwcześniej 2 miesiące po poprzedniej. Od poprzedniej przepustki minęło " . $monthDifference . " dni."]);   
                } else echo json_encode(["status" => false, "msg" => "Data zakończenia przepustki nie może być wcześniejsza niż data jej rozpoczęcia."]);
            }
        // Dodawanie widzeń
        } else {
            // Dodanie do daty rozpoczęcia widzenia wybranej ilości godzin (długość trwania spotkania)
            $endDate = strtotime($date . " " . $end . "hours");
            $endDate = date("Y-m-d\TH:i:s", $endDate);
            
            $monthQuery = "SELECT event_start, event_end FROM calendar_events WHERE MONTH(event_start) = MONTH('$date') AND YEAR(event_start) = YEAR('$date') AND prisoner_id = $prisoner AND type != 'Przepustka'"; // Wybranie godziny rozpoczęcia i zakończenia wszystkich spotkać wybranego więźnia w wybranym miesiącu
            $monthResult = mysqli_query($dbconn, $monthQuery);
            $usedHours = 0; // Zmienna przechowująca liczbę wykorzystanych godzin na widzenia w miesiącu
            if ($monthResult->num_rows > 0) {
                while ($row = $monthResult->fetch_assoc()) {
                    $date1 = strtotime($row['event_start']);
                    $date2 = strtotime($row['event_end']);
                    $usedHours = $usedHours + abs(floor(($date2-$date1)/3600)); 
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
                } else echo json_encode(["status" => false, "msg" => "Spotkanie nie może tyle trwać. Liczba pozostałych godzin na wizyty więźnia w tym miesiącu: " . (3-$usedHours) . "h"]);
            }
        }
    } else echo json_encode(["status" => false, "msg" => "Brakuje niektórych danych."]);
}
?>
