<?php

$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

$query = "SELECT * FROM  `prisoners`";
$result = mysqli_query($dbconn,$query);
$row = mysqli_fetch_array($result);


?>