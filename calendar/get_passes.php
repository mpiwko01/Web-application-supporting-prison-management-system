<?php

$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query = "SELECT prisoner, start_pass, end_pass FROM passes";
$result = $mysqli->query($query);

$passes = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        $pass = array(
            'title' => $row['prisoner'],
            'start' => $row['start_pass'],
            'end' => $row['end_pass'],
        );
        $passes[] = $pass;   
    }
}

header('Content-Type: application/json');

echo json_encode($passes);

?>