<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']!==1))
{
    //$_SESSION['login'] = $login;
    header('Location: logpage.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web prison management system - Home page</title>
    <link rel="stylesheet" href="./style/map.css">
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
                    <a class="nav-link px-lg-3" href="prisoner_panel.php">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="./calendar/calendar.php">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <head>
        <div class="container py-5">
            <h1>MAPA WIĘZIENIA</h1>
            <div class="div-boxes">
                <div class="prison_cell col-12 col-md-4 col-lg-3">
                    <h3>CELA NR 1</h3>
                    <p class="mb-0"><strong>Osadzeni:</strong></p>
                    <span class="prisoner">
                        <?php
                            $cellNumber = 1;
                            include 'display_cell_prisoners.php';
                            $names = $_SESSION['prisoner_names'];
                            foreach ($names as $name) {
                            echo $name . "<br>";
                            }
                        ?>
                    </span>
                    <div class="d-flex cells-buttons p-3">
                        <button id="btn-1" class="btn-add bg-dark text-light">DODAJ WIĘŹNIA</button>
                        <button id="move-1" class="d-none move">PRZENIEŚ WIĘŹNIA</button>
                    </div>
                    
                    
                </div>
                <div class="prison_cell col-12 col-md-4 col-lg-3">
                    <p>CELA NR 2</p>
                    <button id="btn-2" class="btn-add bg-dark text-light">DODAJ WIĘŹNIA</button>
                    <span class="prisoner">
                        <?php
                            $cellNumber = 2;
                            include 'display_cell_prisoners.php';
                            $names = $_SESSION['prisoner_names'];
                            foreach ($names as $name) {
                            echo $name . "<br>";
                            }
                        ?>
                    </span>
                </div>
                <div class="prison_cell col-12 col-md-4 col-lg-3">
                    <p>CELA NR 3</p>
                    <button id="btn-3" class="btn-add bg-dark text-light">DODAJ WIĘŹNIA</button>
                    <span class="prisoner">
                        <?php
                            $cellNumber = 3;
                            include 'display_cell_prisoners.php';
                            $names = $_SESSION['prisoner_names'];
                            foreach ($names as $name) {
                            echo $name . "<br>";
                            }
                        ?>
                    </span>
                </div>
                <div class="prison_cell col-12 col-md-4 col-lg-3">
                    <p>CELA NR 4</p>
                    <button id="btn-4" class="btn-add bg-dark text-light">DODAJ WIĘŹNIA</button>
                    <span class="prisoner">
                        <?php
                            $cellNumber = 4;
                            include 'display_cell_prisoners.php';
                            $names = $_SESSION['prisoner_names'];
                            foreach ($names as $name) {
                            echo $name . "<br>";
                            }
                        ?>
                    </span>
                </div>
                <div class="prison_cell col-12 col-md-4 col-lg-3">
                    <p>CELA NR 5</p>
                    <button id="btn-5" class="btn-add bg-dark text-light">DODAJ WIĘŹNIA</button>
                    <span class="prisoner">
                        <?php
                            $cellNumber = 5;
                            include 'display_cell_prisoners.php';
                            $names = $_SESSION['prisoner_names'];
                            foreach ($names as $name) {
                            echo $name . "<br>";
                            }
                        ?>
                    </span>
                </div>
                <div class="prison_cell col-12 col-md-4 col-lg-3">
                    <p>CELA NR 6</p>
                    <button id="btn-6" class="btn-add bg-dark text-light">DODAJ WIĘŹNIA</button>
                    <span class="prisoner">
                        <?php
                            $cellNumber = 6;
                            include 'display_cell_prisoners.php';
                            $names = $_SESSION['prisoner_names'];
                            foreach ($names as $name) {
                            echo $name . "<br>";
                            }
                        ?>
                    </span>
                </div>
            </div>



            
            <div id="popup" class="pop" style="display: none;">
                <div class="popup-content">
                    <div class="info">
                        <h3 class="pb-3 text-center">WYSZUKAJ WIĘŹNIA</h3>
                        <button type="button" class="btn-close" onclick="closePopup()"></button>
                    </div>

                
                    <div class="dropdown">
                        <input type="text" name="search_box" class="form-control form-control-lg search" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data(this.value)" required />
                        <span id="search_result"></span>
                        <div class="form-group">

                            <label for="start-date">Data<span class="text-danger">*</span></label>
                            <input type="date" class="form-control event_start_date search" name="start_date" id="start-date" placeholder="Data" required>
                        </div>
                        <input type="submit" value="Dodaj" onclick="addPrisoner()" name="dodaj" class="btn-add bg-dark text-light btn-prisoner">
                    </div>

            </div>
                

            </div>
            
        </div>
    </head>

    <script src="./js/map.js"></script>

    <script>

    
    </script>

</body>
</html>