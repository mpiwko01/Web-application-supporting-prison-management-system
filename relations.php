<?php

$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$data = json_decode(file_get_contents("php://input"), true);
$allId = $data['allId'];

$prisonerId = "'" . implode("','", $allId) . "'";


$relations = array();

function FetchRelations($mysqli, $prisonerId) {

    global $relations;

    $query = "SELECT DISTINCT ch1.prisoner_id AS prisoner1_id, ch2.prisoner_id AS prisoner2_id, ch1.cell_nr, 
    GREATEST(ch1.from_date, ch2.from_date) AS overlapping_from, 
    LEAST(ch1.to_date, COALESCE(ch2.to_date, '9999-12-31')) AS overlapping_to
    FROM cell_history ch1
    JOIN cell_history ch2 ON ch1.cell_nr = ch2.cell_nr
    AND ch1.prisoner_id <> ch2.prisoner_id
    WHERE (
        (ch1.from_date <= ch2.to_date AND (ch1.to_date IS NULL OR ch1.to_date >= ch2.from_date OR ch1.to_date >= COALESCE(ch2.to_date, '9999-12-31'))) OR
        (ch1.from_date >= ch2.from_date AND (ch2.to_date IS NULL OR ch2.to_date >= ch1.from_date OR COALESCE(ch2.to_date, '9999-12-31') >= ch1.to_date)) OR
        (ch1.from_date <= ch2.from_date AND (ch1.to_date IS NULL OR ch1.to_date >= ch2.from_date) AND (ch2.to_date IS NULL OR ch2.to_date >= ch1.from_date))
    )
    AND (ch1.prisoner_id IN ($prisonerId) OR ch2.prisoner_id IN ($prisonerId))
    ORDER BY ch1.cell_nr, overlapping_from, prisoner1_id, prisoner2_id;";

    $result = $mysqli->query($query);


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $relation = array(
              "id" => $row["prisoner1_id"], 
              "id2" => $row["prisoner2_id"],
              "cellNumber" => $row["cell_nr"],
              "from" => $row["overlapping_from"],
              "to" => $row["overlapping_to"],
            );

            $relations[] = $relation;
        }
      }
}

foreach ($allId as $id) {
    FetchRelations($mysqli, $id);
}

header('Content-Type: application/json');

if ($relations) {
    echo json_encode($relations);
} else {
    echo json_encode(array("message" => "Brak powiązań"));
    
}

?>