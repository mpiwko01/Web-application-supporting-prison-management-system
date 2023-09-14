<?php

session_start();

if (isset($_POST['password_change'])) {

    $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

    if (isset($_POST['old_password']) && isset($_POST['password1']) && isset($_POST['password2']) && !empty($_POST['old_password']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {

        $password_old = $_POST['old_password']; 
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        $login = $_SESSION['login'];

        if ($password_old == $_SESSION['password']) {

            if ($password1 == $password2) {

                $_SESSION['new_password'] = $password1;
                $new_password = $_SESSION['new_password'];

                $_SESSION['password'] = $_SESSION['new_password'];

                $update_query = "UPDATE `administration` SET password = '$new_password' where id='$login'";

                $result_update = mysqli_query($dbconn, $update_query);
                
                $_SESSION['password_change_try'] = true;

                $_SESSION['password_com'] = '<span>Hasło zostało zmienione!</span>';

                header("Location: panel.php");
               
            }

            else {
                $_SESSION['password_com'] = '<span>Hasła są różne!</span>';
                $_SESSION['password_change_try'] = true;
                header("Location: panel.php");
            }
        }

        else {
            $_SESSION['password_com'] = '<span>Nieprawidłowe hasło!</span>';
            $_SESSION['password_change_try'] = true;
            header("Location: panel.php");   
        }
    }
}
?>