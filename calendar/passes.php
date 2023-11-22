<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['prisoner1']) && isset($data['start_pass']) && isset($data['end_pass'])) {

        $prisoner = $data['prisoner1'];
        $start_pass = $data['start_pass'];
        $end_pass = $data['end_pass'];
        
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $rowcount=mysqli_num_rows(mysqli_query($dbconn,"SELECT * FROM passes"));
        $pass_id=0;
        if($rowcount==0)
        {
            $pass_id = 1;
        }
        else{
            for ($i=0; $i<$rowcount; $i++){
                $test=$i+1;
                $still = mysqli_query($dbconn, "SELECT COUNT(pass_id) AS val FROM passes WHERE pass_id = '$test'");
                $number = mysqli_fetch_assoc($still);
                if ($number['val'] == 0){
                    $pass_id=$test;
                    break;
                }
            }
            if ($pass_id==0){
                $pass_id = (int)$rowcount + 1;
            }
        }

        $insert_query = "INSERT INTO passes (pass_id, prisoner, start_pass, end_pass) VALUES ('$pass_id', '$prisoner', '$start_pass', '$end_pass');";

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