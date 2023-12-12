<?php

$mysqli = new mysqli("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query = "SELECT prisoner_id, cell_nr
FROM cell_history WHERE to_date IS null";

$result = $mysqli->query($query);

$cells=array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cell = array(
          "id" => $row["prisoner_id"], 
          "cellNumber" => $row["cell_nr"],
        );
        $cells[] = $cell;
    }
  }
 
  header('Content-Type: application/json');

  echo json_encode($cells);
  ?>