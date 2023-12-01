<?php
$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
$query = "SELECT * FROM  `prisoners` WHERE `in_prison` = 0  ORDER BY `surname` ASC, `name` ASC";
$result = mysqli_query($dbconn,$query);
?>
