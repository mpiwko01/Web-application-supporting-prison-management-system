<?php

$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query = "SELECT pass_id, prisoner, start_pass, end_pass FROM passes";
$result = $mysqli->query($query);

$passes = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        $pass = array(
            'id' => $row['pass_id'],
            'title' => $row['prisoner'],
            'start' => $row['start_pass'],
            'end' => $row['end_pass'],
            'color' => '#db00ff',
        );
        $passes[] = $pass;   
    }
}

header('Content-Type: application/json');

echo json_encode($passes);

?>