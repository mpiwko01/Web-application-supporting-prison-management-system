<?php

$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$data = json_decode(file_get_contents("php://input"), true);
$prisonerId = $data['prisonerId'];

$query = "SELECT prisoners.prisoner_id as prisoner_id, prisoners.name, prisoners.surname, cell_history.cell_nr, prisoners.sex, prisoners.birth_date
FROM prisoners
INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id
WHERE cell_history.to_date IS NULL
AND prisoners.prisoner_id = '$prisonerId'"; //tu mozna wybrac tez inne rzeczy z polaczenia tych tabel

$result = $mysqli->query($query);

$prisoners = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $prisoner = array(
        "name" => $row["name"], 
        "surname" => $row["surname"],
        "cellNumber" => $row["cell_nr"],
        "prisonerId" => $row["prisoner_id"],
        "sex" => $row["sex"],
        "birthDate" => $row["birth_date"]

      );
      $prisoners[] = $prisoner;
  }
}
else { //jesli liczba zwroconych wierszy = 0 (wiezien w bazie ale nie przypisany do zadnej celi)
    $query = "SELECT * FROM prisoners WHERE `prisoner_id` = '$prisonerId'";

    $result = $mysqli->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $prisoner = array(
              "name" => $row["name"], 
              "surname" => $row["surname"],
              "prisonerId" => $row["prisoner_id"],
              "sex" => $row["sex"],
              "birthDate" => $row["birth_date"],
              "cellNumber" => "jeszcze nie przydzielono"
            );
            $prisoners[] = $prisoner;
        }
    }
}

header('Content-Type: application/json');

echo json_encode($prisoners);


?>
