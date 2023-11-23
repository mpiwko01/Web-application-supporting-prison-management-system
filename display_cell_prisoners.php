<?php


$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");


$data = json_decode(file_get_contents("php://input"), true);
$cellNumbers = $data['cellNumbers'];

$cellNumbersStr = "'" . implode("','", $cellNumbers) . "'";

$query = "SELECT prisoners.name, prisoners.surname, prisoners.birth_date, prisoners.is_reoffender, crimes.severity, cell_history.from_date, cell_history.cell_nr as cellNumber FROM prisoners
INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id where prisoner_sentence.release_date IS NULL AND cell_history.cell_nr IN ($cellNumbersStr) AND cell_history.to_date IS NULL";

$result = $mysqli->query($query);

$prisoners = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $date = new DateTime();
    $prisoner_birth_date = $row["birth_date"];
    $prisoner_birth_date = new DateTime($prisoner_birth_date);
    $format = 'Y'; 
    $prisoner_birth_year = $prisoner_birth_date->format($format);
    $prisonerAge = $date->diff($prisoner_birth_date)->y; 
    $prisoner = array(
        "name" => $row["name"], 
        "surname" => $row["surname"],
        "cellNumber" => $row["cellNumber"],
        "age" => $prisonerAge,
        "isReoffender" => $row["is_reoffender"],
        "severity" => $row["severity"],
        "fromDate" => $row["from_date"]
      );
      $prisoners[] = $prisoner;
  }
}


header('Content-Type: application/json');

echo json_encode($prisoners);


?>
