<?php

session_start();

if (isset($_POST['zaloguj'])) {

    if (empty($_POST['login'])) $_SESSION['error-login'] = '<span>Uzupełnij pole!</span>';

    if (empty($_POST['password'])) $_SESSION['error-password'] = '<span>Uzupełnij pole!</span>';
    
    if (isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])) {

        $login = $_POST['login']; 
        $password = $_POST['password'];

        $host = "localhost";
        $port = 5432;
        $dbname = "Administration";
        $user = "anetabruzda";
        //$dbpassword1 = getenv("DB_PASSWORD");
        $dbpassword2 = 'Aneta30112001'; 


        //LOGOWANIE DO POSTRE
        //$dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$dbpassword1"); //działa
        //$dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$dbpassword2"); //działa
        ///$query = "SELECT * FROM public.\"Users\" WHERE \"id\"='$login' AND \"password\"='$password'";
        //$result = pg_query($dbconn, $query);
        //$row = pg_fetch_assoc($result);

        //LOGOWANIE DO PHPMYADMIN
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $query = "SELECT * FROM  `administration`  WHERE `id`='$login' and `password`='$password'";

        $result = mysqli_query($dbconn,$query);

        $row = mysqli_fetch_array($result);

        if ($row && $row['id'] == $login && $row['password'] == $password) {
            $_SESSION['login'] = $login;

            $_SESSION['name'] = $row['name']; //to dziala
            $_SESSION['surname'] = $row['surname']; 
            
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