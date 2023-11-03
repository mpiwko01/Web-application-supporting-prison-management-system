<?php
$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$data = json_decode(file_get_contents("php://input"), true);
$allId = $data['allId'];

$prisonerId = "'" . implode("','", $allId) . "'";

$prisoners = array();

// Funkcja do pobierania danych więźniów
function fetchPrisonerData($mysqli, $prisonerId)
{
    global $prisoners;

    $query = "SELECT prisoners.prisoner_id, prisoners.name, prisoners.surname, cell_history.cell_nr, prisoners.sex, prisoners.birth_date, prisoners.street, prisoners.house_number, prisoners.city, prisoners.zip_code 
        FROM prisoners
        INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id
        WHERE cell_history.to_date IS NULL
        AND prisoners.prisoner_id IN ($prisonerId)";

    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $prisoner = array(
                "name" => $row["name"],
                "surname" => $row["surname"],
                "cellNumber" => $row["cell_nr"],
                "prisoner_id" => $row["prisoner_id"],
                "sex" => $row["sex"],
                "street" => $row["street"],
                "houseNumber" => $row["house_number"],
                "city" => $row["city"],
                "zipCode" => $row["zip_code"]
            );
            $prisoners[] = $prisoner;
        }
    } else { //jesli liczba zwroconych wierszy = 0 (wiezien w bazie ale nie przypisany do zadnej celi)
        $query = "SELECT * FROM prisoners WHERE prisoner_id = '$prisonerId'";
    
        $result = $mysqli->query($query);
    
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $prisoner = array(
                  "name" => $row["name"], 
                  "surname" => $row["surname"],
                  "prisonerId" => $row["prisoner_id"],
                  "sex" => $row["sex"],
                  "birthDate" => $row["birth_date"],
                  "cellNumber" => "jeszcze nie przydzielono",
                  "street" => $row["street"],
                  "houseNumber" => $row["house_number"],
                  "city" => $row["city"],
                  "zipCode" => $row["zip_code"]
                );
                $prisoners[] = $prisoner;
            }
        }
    }
}

// Pobierz dane więźniów
foreach ($allId as $id) {
    fetchPrisonerData($mysqli, $id);
}

header('Content-Type: application/json');
echo json_encode($prisoners);
?>