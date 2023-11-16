<?php

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$date = new DateTime();
$format = 'Y-m-d'; 
$curDate = $date->format($format);

$query_prisoners = "UPDATE `prisoners` AS p
INNER JOIN `prisoner_sentence` AS ps ON p.prisoner_id = ps.prisoner_id
SET p.in_prison = 0
WHERE ps.to_date = '$curDate'";

$result_prisoners = mysqli_query($dbconn, $query_prisoners);

if ($result_prisoners) {

    $query_cell_history = "UPDATE `cell_history` AS c
    INNER JOIN `prisoner_sentence` AS ps ON c.prisoner_id = ps.prisoner_id
    SET c.to_date = '$curDate'
    WHERE ps.prisoner_id IN (
        SELECT prisoner_id 
        FROM prisoner_sentence 
        WHERE `to_date` = '$curDate' AND release_date IS NULL
    ) AND c.to_date IS NULL";

    $result_cell_history = mysqli_query($dbconn, $query_cell_history);

    $query_prisoner_sentence = "UPDATE `prisoner_sentence` SET `release_date` = '$curDate' WHERE `to_date` = '$curDate' AND `release_date` IS NULL";

    $result_prisoner_sentence = mysqli_query($dbconn, $query_prisoner_sentence);
    
    if ($result_cell_history && $result_prisoner_sentence) {
        echo "Aktualizacja bazy danych zakończona sukcesem.";
    } else {
        echo "Błąd podczas aktualizacji historii cel. " . mysqli_error($dbconn);
    }
} else {
    //response =  "Nic do aktualizacji. ";
}

?>
