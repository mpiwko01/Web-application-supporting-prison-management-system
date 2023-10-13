<?php
session_start();



if (isset($_POST['dodaj'])) {

    $event_name = $_POST['event_name'];
    $event_start_date = date("Y-m-d", strtotime($_POST['event_start_date'])); 
    $event_end_date = date("Y-m-d", strtotime($_POST['event_end_date'])); 

    $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

    $insert_query = "insert into `calendar_event_master`(`event_name`,`event_start_date`,`event_end_date`) values ('".$event_name."','".$event_start_date."','".$event_end_date."')"; 

    //$query = "INSERT INTO calendar_event_master VALUES ('$event_name', '$event_start_date', '$event_end_date')";
    //$query = "INSERT INTO `calendar_event_master` VALUES ('hfhf', '2022-12-22', '2022-12-23')";

    $result = mysqli_query($dbconn, $insert_query);

    $row = mysqli_fetch_array($result);

    if ($result) {
        // Zapytanie SQL zakończone sukcesem
        echo json_encode(["status" => true, "msg" => "Event saved successfully"]);
    } else {
        // Błąd w zapytaniu SQL
        echo json_encode(["status" => false, "msg" => "Error: " . mysqli_error($dbconn)]);
    }

};            

?>
