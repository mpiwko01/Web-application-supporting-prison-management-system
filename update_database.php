<?php

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query_prisoners = "UPDATE `prisoners` AS p
INNER JOIN `prisoner_sentence` AS ps ON p.prisoner_id = ps.prisoner_id
SET p.in_prison = 0
WHERE ps.to_date = CURDATE();";

$result_prisoners = mysqli_query($dbconn, $query_prisoners);

$date = new DateTime();
$format = 'Y-m-d'; 
$curDate = $date->format($format);

if ($result_prisoners && $result_prisoners->num_rows > 0) {
    $query_cell_history = "UPDATE `cell_history` AS c
    INNER JOIN `prisoner_sentence` AS ps ON c.prisoner_id = ps.prisoner_id
    SET c.to_date = '$curDate'
    WHERE c.to_date IS NULL";

    $result_cell_history = mysqli_query($dbconn, $query_cell_history);
    
    if ($result_cell_history) {
        echo "Aktualizacja bazy danych zakończona sukcesem.";
    } else {
        echo "Błąd podczas aktualizacji historii cel. " . mysqli_error($dbconn);
    }
} else {
    //response =  "Nic do aktualizacji. ";
}

?>
