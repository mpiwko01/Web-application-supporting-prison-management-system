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
                    <a class="nav-link px-lg-3" href="./calendar/calendar.php">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <div class="container py-5 box">
            <div class="panel mb-3">
                <div class="panel-content m-0">
                    <div class="id-box m-0 container">
                        <p class="m-0 p-0">ID pracownika:
                            <?php
                                if ($_SESSION['zalogowany'] == 1) {
                                    echo $_SESSION['id'];
                                }
                            ?>
                        </p>
                    </div>
                    <div class="main m-0 row container">
                        <div class="col-lg-2 col-md-12 m-0 p-0">
                            <ul class="d-flex flex-lg-column m-0 p-0">
                                <li class="list-group-item py-1 flex-grow-1"><form action="raport_generator.php" method="post"><input type="submit" name="generuj_raport" class="btn-1 btn px-0 pt-0 pb-2" value="Raport ogólny"></form></li>
                                <li class="list-group-item py-1 flex-grow-1"><input onclick="openPopup()" type="button" value="Zmień hasło" class="btn-1 btn px-0 pt-0 pb-2"></li>
                                <li class="list-group-item py-1 flex-grow-1"><form action="wylogowanie.php" method="post"><input type="submit" value="Wyloguj się" name="wyloguj" class="btn-4 btn px-0 pt-0 pb-2"></form></li>
                            </ul>
                        </div>

                        <div class="col-lg-10 col-md-12 m-0 p-0 main-content">

                            <div class="row">
                                <div class="info col-12 col-md-8">
                                    <div class="info-label">Dane personalne:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                            <div class="label col-12  col-sm-6">Nazwisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['surname']; ?></div>
                                        </div>
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="info col-12 col-md-8">
                                    <div class="info-label">Dane adresowe:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                            <div class="label col-12  col-sm-6">Nazwisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['surname']; ?></div>
                                        </div>
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                            <div class="label col-12 col-sm-6">Nazwisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['surname']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="info col-12 col-md-8">
                                    <div class="info-label">Dane kontaktowe:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                            <div class="label col-12  col-sm-6">Nazwisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['surname']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="info col-12 col-md-8">
                                    <div class="info-label">Dane zatrudnienia:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                            <div class="label col-12  col-sm-6">Nazwisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['surname']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            

                            

                           
                        </div> 
                    </div>
                </div>
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
