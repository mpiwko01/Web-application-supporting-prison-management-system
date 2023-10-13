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
                    <a class="nav-link px-lg-3" href="map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <div class="container py-5">
            <div class="panel row px-5 py-4 justify-content-center">
                <div class="id-box row mx-3 px-0">
                    <p class="col-12 m-0 p-0">ID pracownika:
                        <?php
                            if ($_SESSION['zalogowany'] == 1) {
                                echo $_SESSION['id'];
                            }
                        ?>
                    </p>
                </div>
                <div class="container row mx-3 px-0">
                    <div class="sidebar col-2 justify-content-start mx-0 px-0">
                        <ul class="text-left px-0 pb-5 mt-5 align-items-center">
                            <li class="list-group-item py-1"><button onclick="openTab(this)" class="btn-1 btn px-0 pt-0 pb-2">Informacje</button></li>
                            <li class="list-group-item py-1"><button onclick="openTab(this)" class="btn-2 btn px-0 pt-0 pb-2">Raporty i wnioski</button></li>
                            <li class="list-group-item py-1"><button onclick="openTab(this)" class="btn-3 btn px-0 pt-0 pb-2">Logowania</button></li>
                            <li class="list-group-item py-1"><button onclick="openTab(this)" class="btn-4 btn px-0 pt-0 pb-2">Ustawienia</button></li>
                        </ul>
                        <form action="wylogowanie.php" method="post" id="wyloguj" class="mx-0 px-0 justify-content-center log-out">
                            <input type="submit" value="Wyloguj się" name="wyloguj" class="log-out">
                        </form> 
                    </div>

                    <div class="main-content col-10 mx-0 ps-5 pe-0">
                        <div class="personal-info">
                            <div class="personal-info-box row">
                                <div class="personal-data col-8">
                                    <p class="mb-3 info-label">Dane personalne:</p>
                                    <div class="box-data row mb-4">
                                        <div class="label col-6">Imię:  
                                            <?php
                                                if ($_SESSION['zalogowany'] == 1) {
                                                    echo $_SESSION['name'];
                                                }
                                            ?>
                                        </div>
                                        <div class="label col-6">Nazwisko: 
                                            <?php
                                                if ($_SESSION['zalogowany'] == 1) {
                                                    echo $_SESSION['surname'];
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-image col-4 d-flex justify-content-end">
                                    <img src="" alt="" class="image-holder">
                            </div>

                            <div class="contact-info-box row">
                                <div class="contact-data col-8">
                                    <p class="mb-3 info-label">Dane kontaktowe:</p>
                                    <div class="box-data row mb-4">
                                        <div class="label col-6">Numer telefonu: </div>
                                        <div class="label col-6">Adres zamieszkania: </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hire-info-box row">
                                <div class="hire-data col-8">
                                    <p class="mb-3 info-label">Dane zatrudnienia:</p>
                                    <div class="box-data row mb-4">
                                        <div class="label col-6">Data zatrudnienia: 

                                        </div>
                                        <div class="label col-6">Stanowisko pracy: 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="raports d-none">
                            <p><a class="link_download" href="./docs/urlop.pdf" download>Wniosek o urlop</a></p>
                            <form action="raport_generator.php" method="post" class="py-3">
                                <input type="submit" name="generuj_raport" value="Generuj raport PDF">
                            </form>
                        </div>

                        <div class="logs d-none">
                            <p>Ostatnie logowanie:
                                <?php
                                    echo $_SESSION['resultString'];
                                ?>
                            </p>
                        </div>

                        <div class="settings d-none">
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
                            <div id="com1" class="pop" style="display: none;">
                                <?php
                                    echo $_SESSION['password_com'];
                                ?>;
                                <input onclick="closeCom()" type="button" value="Zamknij" name="Zamknij">
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
