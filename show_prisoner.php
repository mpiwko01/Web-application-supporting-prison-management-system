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

    $query = "SELECT prisoners.prisoner_id, prisoners.name, prisoners.surname, cell_history.cell_nr, prisoners.sex, prisoners.birth_date, prisoners.street, prisoners.house_number, prisoners.city, prisoners.zip_code, prisoners.in_prison, prisoner_sentence.from_date, prisoner_sentence.to_date, crimes.description, crimes.crime_id, prisoner_sentence.release_date
        FROM prisoners
        INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id
        INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id
        INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id
        WHERE cell_history.to_date IS NULL
        AND prisoner_sentence.sentence_id = (SELECT MAX(sentence_id) FROM prisoner_sentence WHERE prisoner_sentence.prisoner_id = '$prisonerId')";

    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $prisoner = array(
                "name" => $row["name"],
                "surname" => $row["surname"],
                "cellNumber" => $row["cell_nr"],
                "prisonerId" => $row["prisoner_id"],
                "sex" => $row["sex"],
                "birthDate" => $row["birth_date"],
                "street" => $row["street"],
                "houseNumber" => $row["house_number"],
                "city" => $row["city"],
                "zipCode" => $row["zip_code"],
                "inPrison" => $row["in_prison"],
                "startDate" => $row["from_date"],
                "endDate" => $row["to_date"],
                "crime" => $row["description"],
                "crime_id" => $row["crime_id"],
                "release" => $row["release_date"]
            );
            $prisoners[] = $prisoner;
        }
    } else { //jesli liczba zwroconych wierszy = 0 (wiezien w bazie ale nie przypisany do zadnej celi)

        $query = "SELECT prisoners.prisoner_id, prisoners.name, prisoners.surname, prisoners.sex, prisoners.birth_date, prisoners.street, prisoners.house_number, prisoners.city, prisoners.zip_code, prisoners.in_prison, prisoner_sentence.from_date, prisoner_sentence.to_date, crimes.description, crimes.crime_id, prisoner_sentence.release_date
        FROM prisoners 
        INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id 
        INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id
        WHERE prisoner_sentence.sentence_id = (SELECT MAX(sentence_id) FROM prisoner_sentence WHERE prisoner_sentence.prisoner_id = '$prisonerId');";
    
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
                  "zipCode" => $row["zip_code"],
                  "inPrison" => $row["in_prison"],
                  "startDate" => $row["from_date"],
                  "endDate" => $row["to_date"],
                  "crime" => $row["description"],
                  "crime_id" => $row["crime_id"],
                  "release" => $row["release_date"]
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