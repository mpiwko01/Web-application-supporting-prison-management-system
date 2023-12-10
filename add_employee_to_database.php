<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['surname']) && !empty($_POST['surname']) && isset($_POST['sex']) && !empty($_POST['sex']) && isset($_POST['birthDate']) && !empty($_POST['birthDate']) && isset($_POST['street']) && !empty($_POST['street'])&& isset($_POST['houseNumber']) && !empty($_POST['houseNumber'])&& isset($_POST['city']) && !empty($_POST['city']) && isset($_POST['zipCode']) && !empty($_POST['zipCode']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['phoneNumber']) && !empty($_POST['phoneNumber']) && isset($_POST['position']) && !empty($_POST['position']) && isset($_POST['hireDate']) && !empty($_POST['hireDate'])) {

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $sex = $_POST['sex'];
        $birthDate = $_POST['birthDate'];
        $street = $_POST['street'];
        $houseNumber = $_POST['houseNumber'];
        $zipCode = $_POST['zipCode'];
        $city = $_POST['city'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $position = $_POST['position'];
        $hireDate = $_POST['hireDate'];

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        // Zapytanie o ostatnie ID pracownika
        $query_employee_id = "SELECT MAX(id) AS last_employee_id FROM administration";
        $result_employee_id = mysqli_query($dbconn, $query_employee_id);

        if ($result_employee_id && $result_employee_id->num_rows > 0) {
            $row = $result_employee_id->fetch_assoc();
            $lastEmployeeID = $row["last_employee_id"];
        }
        else {
            $lastEmployeeID = 0;
        }
        $employeeID = $lastEmployeeID + 1;

        // Wstawienie pracownika do tabeli administration
        $query_employees = "INSERT INTO administration VALUES ('$employeeID', '$employeeID', '$name', '$surname', '$sex', '$birthDate', '$street', '$houseNumber', '$city', '$zipCode', '$email', '$phoneNumber', '$hireDate', NULL, '$position')";
        $result_employees = mysqli_query($dbconn, $query_employees);
        
        if ($result_employees) {
            echo "Dodano nowego pracownika.";
        } 
    }  
}

header('Content-Type: application/json');
?>