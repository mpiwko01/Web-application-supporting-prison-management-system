<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['employeeId']) && !empty($_POST['employeeId'])) {

        $employeeID = $_POST['employeeId'];

        $date = new DateTime();
        $format = 'Y-m-d'; 
        $curDate = $date->format($format);

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $query = "UPDATE `administration` SET `end_date` = '$curDate' WHERE `id`= '$employeeID'";
        $result = mysqli_query($dbconn, $query);

        if ($result) echo "Pracownik został usunięty.";
    }  
}

header('Content-Type: application/json');
//echo json_encode($response);
?>