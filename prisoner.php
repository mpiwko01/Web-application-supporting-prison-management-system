<?php

session_start();

if (isset($_POST['prisoner_id']) && !empty($_POST['prisoner_id'])) {

    $prisoner_id = $_POST['prisoner_id'];
    $_SESSION['prisoner_id'] = $prisoner_id;

    $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

    $query_prisoner_id = "SELECT * FROM  `prisoners`  WHERE `nr`='$prisoner_id'";

    $result_prisoner_id = mysqli_query($dbconn,$query_prisoner_id);

    $row_prisoner_id = mysqli_fetch_array($result_prisoner_id);

    if ($row_prisoner_id && $row_prisoner_id['nr'] == $prisoner_id) {

        $_SESSION['nr'] = $row_prisoner_id['nr'];
        $_SESSION['name_prisoner'] = $row_prisoner_id['name'];
        $_SESSION['surname_prisoner'] = $row_prisoner_id['surname'];

        header("Location: prisoner_panel.php");
    };

};

?>