<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']!==1))
{
    
    header('Location: logpage.php');
    exit();
}

//$_SESSION['name'] = $name;
//$query = "SELECT * FROM Users WHERE username = $1";
//$name_query = "SELECT name FROM public.\"Users\" WHERE \"id\"='$login' AND \"password\"='$password'";
//$name = $pdo->query($name_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web prison management system - Home page</title>
    <link rel="stylesheet" href="./style/homepage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a6f2b46177.js" crossorigin="anonymous"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 sticky-top">
        <div class="container ">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-magnifying-glass"></i>SearchJail</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto text-uppercase">
                    <a class="nav-link px-lg-3" href="#home">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="aboutus.html">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="services.html">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <p>Jesteś zalogowany jako:
            <?php
                if ($_SESSION['zalogowany'] = 1) {
                echo $_SESSION['name'];
                //echo $name;

                }
            ?>
        </p>
        <p>Ostatnie logowanie: 
            <?php   
                $czas_teraz = new DateTime();
                $_SESSION['czas'] = $czas_teraz;
                $format_czasu = 'Y-m-d H:i:s'; 
                $sformatowany_czas = $czas_teraz->format($format_czasu);
                echo $sformatowany_czas;
            ?>
        </p>
        <form action="wylogowanie.php" method="post" id="wyloguj">
            <input type="submit" value="Wyloguj się" name="wyloguj">
        </form>
        
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    
    <script>
        //const lastLog = localStorage.getItem('log');
        //document.getElementById('lastLog').textContent = lastLog;
    </script>
</body>

</html>