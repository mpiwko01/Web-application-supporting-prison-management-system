<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']!==1))
{ 
    header('Location: logpage.php');
    exit();
}

$passwordSet = isset($_SESSION['password_change_try']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web prison management system - Home page</title>
    <link rel="stylesheet" href="./style/panel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a6f2b46177.js" crossorigin="anonymous"></script>
</head>

<body onload="isPasswordChanged()">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 sticky-top">
        <div class="container ">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-magnifying-glass"></i>SearchJail</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto text-uppercase">
                    <a class="nav-link px-lg-3" href="prisoner_panel.php">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="./calendar/calendar.html">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="services.html">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <div class="container py-5">
            <div class="panel">

                <div class="personal-info">
                    <img src="" alt="" class="image-holder">
                    <div class="text_info">
                        <p>Jesteś zalogowany jako:
                            <?php
                                if ($_SESSION['zalogowany'] == 1) {
                                    echo $_SESSION['name'];
                                }
                            ?>
                        </p>
                        <p>Ostatnie logowanie:
                            <?php
                                echo $_SESSION['resultString'];
                            ?>
                        </p>
                    </div>
               
                </div>
        
                <p><a class="link_download" href="./docs/urlop.pdf" download>Wniosek o urlop</a></p>

                <form action="raport_generator.php" method="post" class="py-3">
                    <input type="submit" name="generuj_raport" value="Generuj raport PDF">
                </form>

                <input onclick="openPopup()" type="button"value="Zmień hasło">

        <div id="popup" class="pop" style="display: none;">
            <form action="password_change.php" method="post">
                <input type="password" name="old_password" id="old_password" placeholder="wpisz stare hasło">
                <input type="password" name="password1" id="password1" placeholder="wpisz nowe hasło">
                <input type="password" name="password2" id="password2" placeholder="wpisz ponowanie nowe hasło">

                <input onclick="closePopup()" type="submit" value="Zapisz zmiany" name="password_change">
                
            </form>

            <input onclick="closePopup()" type="button" value="Zamknij" name="Zamknij"> 
        </div>


        <form action="wylogowanie.php" method="post" id="wyloguj">
            <input type="submit" value="Wyloguj się" name="wyloguj">
        </form>

        <div id="com1" class="pop" style="display: none;">
            <?php
                echo $_SESSION['password_com'];
            ?>;
            <input onclick="closeCom()" type="button" value="Zamknij" name="Zamknij">
        </div>
        
    </div>
       
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    <script>

        function openPopup() {
            document.getElementById('popup').style.display = 'block';
            
        };

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        };

        function closeCom() {
            document.querySelector("#com1").style.display = 'none';

            fetch('remove_password_change_try.php') 
        };

        var passwordSet = <?php echo json_encode($passwordSet)?>;

        function isPasswordChanged() {
            var hasBeenDisplayed = sessionStorage.getItem('passwordChangedDisplayed');

            if (passwordSet && !hasBeenDisplayed) {
                document.getElementById('com1').style.display = 'block';
            }
        };

    </script>

    <script src="./js/panel.js"></script>

</body>

</html>
