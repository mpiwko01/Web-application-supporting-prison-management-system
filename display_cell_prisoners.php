<?php

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query = "SELECT prisoners.name, prisoners.surname FROM prisoners
INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id where cell_history.cell_nr='$cellNumber'";

$result = mysqli_query($dbconn,$query);

$names = array();

while ($row = mysqli_fetch_assoc($result)) {
    $names[] = $row['name'] . ' ' . $row['surname']; 
}

$_SESSION['prisoner_names'] = $names;

while ($row = mysqli_fetch_assoc($result)) {
  echo $row['name'] . "<br>";
}

?>