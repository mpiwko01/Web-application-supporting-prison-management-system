<?php
$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
$query = "SELECT * FROM  `prisoners`";
$result = mysqli_query($dbconn,$query);

$prisoners=array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prisoner = array(
          "id" => $row["prisoner_id"], 
          "name" => $row["name"],
          "surname" => $row["surname"]
        );
        $prisoners[] = $prisoner;
    }
  }

  header('Content-Type: application/json');

  echo json_encode($prisoners);
?>
