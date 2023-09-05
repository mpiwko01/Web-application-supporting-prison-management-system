<?php

session_start();

if (isset($_POST['zaloguj'])) {

    if (empty($_POST['login'])) $_SESSION['error-login'] = '<span>Uzupełnij pole!</span>';

    if (empty($_POST['password'])) $_SESSION['error-password'] = '<span>Uzupełnij pole!</span>';
    
    if (isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])) {

        

        $login = $_POST['login']; 
        $password = $_POST['password'];

        $dbconn = pg_connect("host=localhost port=5432 dbname=Administration user=anetabruzda password=Aneta30112001");

        $query = "SELECT * FROM public.\"Users\" WHERE \"id\"='$login' AND \"password\"='$password'";
        //$name_query = "SELECT name FROM public.\"Users\" WHERE \"id\"='$login' AND \"password\"='$password'";

        $result = pg_query($dbconn, $query);

        //$name = pg_query($dbconn, $name_query);

        $row = pg_fetch_assoc($result);

        if ($row && $row['id'] == $login && $row['password'] == $password) {
            $_SESSION['login'] = $login;

            $_SESSION['name'] = $row['name']; //to dziala
            
            //$name = $_SESSION['name'];
            $_SESSION['zalogowany'] = 1;
            header("Location: homepage.php");}

        else {
            $_SESSION['error'] = '<span>Nieprawidłowy login lub hasło!</span>';
            header("Location: logpage.php");
        }
}
else {
    header("Location: logpage.php");  
}
}
?>