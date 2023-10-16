<?php

$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query = "SELECT event_name, event_start, event_id, visitors, prisoner, event_end, color FROM calendar_event_master";
$result = $mysqli->query($query);

$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event = array(
            'title' => $row['event_name'],
            'start' => $row['event_start'],
            'end' => $row['event_end'],
            'visitors' => $row['visitors'],
            'prisoner' => $row['prisoner'],
            'color' => $row['color'],
            'id' => $row['event_id'],
        );
        $events[] = $event;   
    }
}

header('Content-Type: application/json');

echo json_encode($events);

?>
