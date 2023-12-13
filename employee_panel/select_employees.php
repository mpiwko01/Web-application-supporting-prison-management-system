<?php
//OBECNI PRACOWNICY
$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
$query = "SELECT * FROM  `administration` WHERE `end_date` IS NULL ORDER BY `surname` ASC, `name` ASC";
$result = mysqli_query($dbconn,$query);
?>