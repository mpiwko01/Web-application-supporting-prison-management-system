<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['prisoner1']) && isset($data['startPass']) && isset($data['endPass'])) {

        $prisoner = $data['prisoner1'];
        $startPass = $data['startPass'];
        $endPass = $data['endPass'];
        
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $rowcount=mysqli_num_rows(mysqli_query($dbconn,"SELECT * FROM passes"));
        $passId = 0;
        if($rowcount == 0) $passId = 1;
        else{
            for ($i=0; $i<$rowcount; $i++){
                $test = $i+1;
                $still = mysqli_query($dbconn, "SELECT COUNT(passId) AS val FROM passes WHERE passId = '$test'");
                $number = mysqli_fetch_assoc($still);
                if ($number['val'] == 0){
                    $passId = $test;
                    break;
                }
            }
            if ($passId==0) $passId = (int)$rowcount + 1;
        }

        $insert_query = "INSERT INTO passes (pass_id, prisoner, start_pass, end_pass) VALUES ('$passId', '$prisoner', '$startPass', '$endPass');";

        $result = mysqli_query($dbconn, $insert_query);

        if ($result) {
                // Zapytanie SQL zakończone sukcesem
            echo json_encode(["status" => true, "msg" => "Event updated successfully"]);
        } else {
                // Błąd w zapytaniu SQL
            echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
        }
    }
}

?>