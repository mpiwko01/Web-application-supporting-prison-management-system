<?php
session_start();

// Sprawdź, czy dane zostały przesłane przez metodę POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);
    $event_name = $data['event_name'];
    $date = $data['date'];
    $end = $data['end'];
    $visitors = $data['visitor'];
    $prisoner = $data['prisoner'];
    $color = $data['color'];
   
    // Sprawdź, czy dane istnieją w zapytaniu POST
    try {
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
        $rowcount=mysqli_num_rows(mysqli_query($dbconn,"SELECT * FROM calendar_event_master"));
        $event_id=0;
        if($rowcount==0)
        {
            $event_id = 1;
        }
        else{
            for ($i=0; $i<$rowcount; $i++){
                $test=$i+1;
                $still = mysqli_query($dbconn, "SELECT COUNT(event_id) AS val FROM calendar_event_master WHERE event_id = '$test'");
                $number = mysqli_fetch_assoc($still);
                if ($number['val'] == 0){
                    $event_id=$test;
                    break;
                }
            }
            if ($event_id==0){
                $event_id = (int)$rowcount + 1;
            }
        }

        $insert_query = "INSERT INTO calendar_event_master (event_name, event_start, event_id, visitors, prisoner, event_end, color) VALUES ('$event_name','$date', '$event_id','$visitors', '$prisoner','$end', '$color')"; 

        $result = mysqli_query($dbconn, $insert_query);

        if ($result) {
            // Zapytanie SQL zakończone sukcesem
            echo json_encode(["status" => true, "msg" => "Event saved successfully", "event_id" => $event_id]);
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
