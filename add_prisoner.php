<?php

session_start();

if (isset($_POST['prisoner_add'])) {

        $prisoner_id = $_POST['prisoner_add_id'];
        $cell_number = $_POST['prisoner_add_cell_number'];

        //LOGOWANIE DO PHPMYADMIN
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $query = "INSERT INTO cell_history VALUES ('$prisoner_id', '$cell_number', '2022-12-10', '2022-12-15')"; //daty do poprawy, na razie na sztywno wstawione

        $result = mysqli_query($dbconn,$query);

    }
?>