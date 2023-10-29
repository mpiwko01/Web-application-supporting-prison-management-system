<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']!==1))
{
    
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
    <link rel="stylesheet" href="./style/prisoner.css">
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
                    <a class="nav-link px-lg-3" href="./map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
       
        </nav>

        <header>
        <div class="container py-5 box">
            <h1 class="text-center pb-5">WYSZUKIWARKA WIĘŹNIÓW</h1>
            <div class="dropdown pb-3">
                <input type="text" name="search_box" class="form-control form-control-lg" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data(this.value)" />
		        <span id="search_result"></span>
            </div>
            <div class="buttons">
                <button id="table-btn"class="btn-add bg-dark text-light mb-3" >Wyświetl wszystko</button>
                <button id="add_prisoner"class="btn-add bg-dark text-light mb-3" >Dodaj więźnia do systemu</button>
            </div>
            

            <div class="table d-none">
            <table class="my-table">
                <tr>
                    <th class="number">Nr</th>
                    <th class="prisoner_id">ID</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Płeć</th>
                    <th>Data urodzenia</th>
                </tr>
                <?php

                    include 'select_all.php';

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='id_data'>" . $row['prisoner_id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['surname'] . "</td>";
                    echo "<td>" . $row['sex'] . "</td>";
                    echo "<td>" . $row['birth_date'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            </div>

            <!-- MODUŁ WIĘŹNIA -->
            <div  class="prisoner-popup d-none">
                <div class="popup-content">
                    <div class="personal-info">
                        <img class="prisoner_jpg" src="https://www.telepolis.pl/images/2022/06/zdjecia-fotomontaze-przerobki-ai.jpg" alt="">
                        <div class="data">
                            <span class="d-flex"><span class="me-2">Imię:</span><span class="space_name"></span></span>
                            <span class="d-flex"><span class="me-2">Nazwisko:</span><span class="space_surname"></span></span>
                            <span class="d-flex"><span class="me-2">Płeć:</span><span class="space_sex"></span></span>
                            <span class="d-flex"><span class="me-2">Data urodzenia:</span><span class="space_birth_date"></span></span>
                            <span class="d-flex"><span class="me-2">Wiek:</span><span class="space_age"></span></span>
                        </div>
                        <button type="button" class="btn-close" onclick="closePopup()"></button>
                    </div>
                    <div class="more-info">
                        <span class="d-flex"><span class="me-2">Obecna cela:</span><span class="space_cell"></span></span>
                        <span>Historia:</span>
                        <span>Parametry więźnia:</span>
                    </div>
                </div>


            </div>

        </div>

    </header>
    <script src="./js/prisoner.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    
</body>

</html> 