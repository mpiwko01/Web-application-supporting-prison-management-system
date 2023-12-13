<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['oldPassword']) && isset($_POST['password1']) && isset($_POST['password2']) && !empty($_POST['oldPassword']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $password_old = $_POST['oldPassword']; 
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        $login = $_SESSION['login'];

        if ($password_old == $_SESSION['password']) {

            if ($password1 == $password2) {

                $_SESSION['new_password'] = $password1;
                $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                $new_password = $_SESSION['new_password'];

                $_SESSION['password'] = $_SESSION['new_password'];

                $update_query = "UPDATE `administration` SET password = '$hashed_password' where id='$login'";

                $result_update = mysqli_query($dbconn, $update_query);
                
                echo "Zmieniono hasło";
               
            }
            else echo "Hasła są różne!";
        }
        else echo "Nieprawidłowe hasło!";   
    }
    else if (empty($_POST['oldPassword'])) echo "Uzupełnij pole!1";   
    else if (empty($_POST['password1'])) echo "Uzupełnij pole!2";
    else if (empty($_POST['password2'])) echo "Uzupełnij pole!3";   
}

header('Content-Type: application/json');

?>