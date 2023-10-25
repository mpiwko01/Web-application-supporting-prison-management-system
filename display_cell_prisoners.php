<?php


$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");


$data = json_decode(file_get_contents("php://input"), true);
$cellNumbers = $data['cellNumbers'];

$cellNumbersStr = "'" . implode("','", $cellNumbers) . "'";

$query = "SELECT prisoners.name, prisoners.surname, cell_history.cell_nr as cellNumber FROM prisoners
INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id where cell_history.cell_nr IN ($cellNumbersStr)";

$result = $mysqli->query($query);

$prisoners = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $prisoner = array(
        "name" => $row["name"], 
        "surname" => $row["surname"],
        "cellNumber" => $row["cellNumber"],
      );
      $prisoners[] = $prisoner;

     
  }
}


header('Content-Type: application/json');

echo json_encode($prisoners);


?>
