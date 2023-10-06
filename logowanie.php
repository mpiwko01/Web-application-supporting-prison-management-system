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
            $_SESSION['password'] = $password;

            //$_SESSION['login'] = $row['id'];
            //$login = $_SESSION['login'];
            //$_SESSION['password'] = $row['password'];
            $_SESSION['name'] = $row['name']; //to dziala
            $_SESSION['surname'] = $row['surname'];
            $_SESSION['id'] = $row['id'];
            
            $_SESSION['zalogowany'] = 1;

            $czas_teraz = new DateTime();
            $_SESSION['czas'] = $czas_teraz;
            $format_czasu = 'Y-m-d H:i:s'; 
            $sformatowany_czas = $czas_teraz->format($format_czasu);

            $date_only = 'Y-m-d';
            $time_only = 'H:i:s';

            $sformatowany_date_only = $czas_teraz->format($date_only);
            $sformatowany_time_only = $czas_teraz->format($time_only);

            $query_log = "INSERT INTO logs VALUES ('$login', '$sformatowany_date_only', '$sformatowany_time_only')";
            $result = mysqli_query($dbconn, $query_log);

            $sorted_query = "SELECT date_log, time_log from logs order by date_log desc, time_log desc limit 1 offset 1";
            $result_sorted_query = mysqli_query($dbconn, $sorted_query);

            if ($result_sorted_query) {
                 //$row = pg_fetch_assoc($result_sorted_query); postgre
                $row = mysqli_fetch_array($result_sorted_query); //phpmyadmin
                if ($row) {
                    $dateLog = $row['date_log'];
                    $timeLog = $row['time_log'];
                    $_SESSION['resultString'] = "$dateLog $timeLog";
                   
                    };
            };

            $query_logs_counter = "SELECT COUNT(*) as logs_counter FROM logs";

            $result_logs_counter = mysqli_query($dbconn, $query_logs_counter);

            if ($result_logs_counter) {
                $row_counter = mysqli_fetch_assoc($result_logs_counter);
                $all_logs = $row_counter['logs_counter'];
            
                if ($all_logs >= 9) {

                    $query_drop = "DELETE FROM logs
                        WHERE (date_log, time_log) NOT IN (
                        SELECT MAX(date_log), MAX(time_log) FROM logs where user='1')
                        AND (date_log, time_log) NOT IN (
                        SELECT MAX(date_log), MAX(time_log) FROM logs where user='2')
                        AND (date_log, time_log) NOT IN (
                        SELECT MAX(date_log), MAX(time_log) FROM logs where user='3')";

                    $result_drop = mysqli_query($dbconn, $query_drop);
                };
            };

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