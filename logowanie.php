<?php

session_start();

if (isset($_POST['zaloguj'])) {

    if (empty($_POST['login'])) $_SESSION['error-login'] = '<span>Uzupełnij pole!</span>';

    if (empty($_POST['password'])) $_SESSION['error-password'] = '<span>Uzupełnij pole!</span>';
    
    if (isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])) {

        $login = $_POST['login']; 
        $password = $_POST['password'];
        //$password = hash("sha512",$_POST['password']);

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $query = "SELECT * FROM  `administration`  WHERE `id`='$login'";

        $result = mysqli_query($dbconn,$query);

        $row = mysqli_fetch_array($result);
        $passwordDatabase = $row['password'];

        if ($row && $row['id'] == $login && password_verify($password, $passwordDatabase) && $row['end_date'] == NULL) {
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;

            $_SESSION['name'] = $row['name']; 
            $_SESSION['surname'] = $row['surname'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['birthDate'] = $row['birth_date'];
            $_SESSION['street'] = $row['street'];
            $_SESSION['houseNumber'] = $row['house_number'];
            $_SESSION['city'] = $row['city'];
            $_SESSION['zipCode'] = $row['zip_code'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['number'] = $row['number'];
            $_SESSION['hireDate'] = $row['hire_date'];
            $_SESSION['position'] = $row['position'];
            
            $_SESSION['zalogowany'] = 1;

            $czas_teraz = new DateTime();
            $_SESSION['czas'] = $czas_teraz;
            $format_czasu = 'Y-m-d H:i:s'; 
            $sformatowany_czas = $czas_teraz->format($format_czasu);

            $date_only = 'Y-m-d';
            $time_only = 'H:i:s';

            $sformatowany_date_only = $czas_teraz->format($date_only);
            $sformatowany_time_only = $czas_teraz->format($time_only);

            header("Location: prisoner_panel.php");}

        else {
            $_SESSION['error'] = '<span>Nieprawidłowy login lub hasło!</span>';
            header("Location: logpage.php");
        }
}
else header("Location: logpage.php");  

}
?>