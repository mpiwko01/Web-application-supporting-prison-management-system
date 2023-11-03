<?php
$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");


$query = "SELECT prisoners.prisoner_id, prisoners.name, prisoners.surname
FROM prisoners
WHERE NOT EXISTS (
    SELECT 1
    FROM cell_history
    WHERE cell_history.prisoner_id = prisoners.prisoner_id
) AND `in_prison` = 1";

$result = $mysqli->query($query);

$prisoners = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prisoner = array(
          "name" => $row["name"], 
          "surname" => $row["surname"],
        );
        $prisoners[] = $prisoner;
    }
  }


header('Content-Type: application/json');
echo json_encode($prisoners);
?>