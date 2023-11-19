<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['prisoner1']) && isset($data['start_pass']) && isset($data['end_pass'])) {

        $prisoner = $data['prisoner1'];
        $start_pass = $data['start_pass'];
        $end_pass = $data['end_pass'];
        
       

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $insert_query = "INSERT INTO passes (prisoner, start_pass, end_pass) VALUES ('$prisoner', '$start_pass', '$end_pass');";


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