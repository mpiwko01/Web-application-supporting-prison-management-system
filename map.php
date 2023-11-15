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
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web prison management system - Home page</title>
    <link rel="stylesheet" href="./style/map.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a6f2b46177.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
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

    <header>
        <div class="container py-5">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1>MAPA WIĘZIENIA</h1>
                <button id="move" class="d-none move my-3 " onclick="movePopup()">PRZENIEŚ WIĘŹNIA</button>
                <strong><p class="mb-0">Lista nieprzypisanych więźniów:</p></strong>
                <span class="prisoner-list text-center m-3"></span>
            </div>
            
            <div class="div-boxes">
                <div class="prison_cell col-12 col-md-4 col-lg-3" id="1">
                    <h3 class="nr_celi" >CELA NR 1</h3>
                    <strong><p class="mb-0  list_of"></p></strong>
                    <div class="space_for_prisoners"></div>

                    <button id="btn-1" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    
                    
                </div>
                <div class="prison_cell col-12 col-md-4 col-lg-3" id="2">
                    <h3 class="nr_celi" >CELA NR 2</h3>
                    <strong><p class="mb-0  list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-2" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
        
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="3">
                    <h3 class="nr_celi" >CELA NR 3</h3>
                    <strong><p class="mb-0  list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-3" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                   
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="4">
                    <h3 class="nr_celi" >CELA NR 4</h3>
                    <strong><p class="mb-0  list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-4" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="5">
                    <h3 class="nr_celi" >CELA NR 5</h3>
                    <strong><p class="mb-0  list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-5" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                   
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="6">
                    <h3 class="nr_celi" >CELA NR 6</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-6" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="7">
                    <h3 class="nr_celi" >CELA NR 7</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-7" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3"  id="8">
                    <h3 class="nr_celi">CELA NR 8</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-8" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3"  id="9">
                    <h3 class="nr_celi">CELA NR 9</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-9" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="10">
                    <h3 class="nr_celi" >CELA NR 10</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-10" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="11">
                    <h3 class="nr_celi" >CELA NR 11</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-11" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

                <div class="prison_cell col-12 col-md-4 col-lg-3" id="12">
                    <h3 class="nr_celi" >CELA NR 12</h3>
                    <strong><p class="mb-0 list_of"></p></strong>
                    <div class="space_for_prisoners"></div>
                    <button id="btn-12" class="btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                </div>

            </div>



            
            <div id="popup" class="pop" style="display: none;">
                <div class="popup-content">
                    <div class="info">
                        <h3 class="pb-3 text-center">WYSZUKAJ WIĘŹNIA</h3>
                        <button type="button" class="btn-close" onclick="closePopup('popup')"></button>
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

            <div id="popup1" class="move-popup"  style="display:none">
                <div class="popup-content1">
                    <div class="info">
                        <h3 class="pb-3 text-center">PRZENIEŚ WIĘŹNIA</h3>
                        <button type="button" class="btn-close" onclick="closePopup('popup1')"></button>
                    </div>
            
                    <div class="dropdown">
                        <label>Którego więźnia chcesz przenieść?</label>
                        <input type="text" name="search_box1" class="form-control form-control-lg move-search" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data2(this.value)" required />
                        <span id="search_result1"></span>
                        <div class="form-group">
                                <label for="start-date1">Data<span class="text-danger">*</span></label>
                                <input type="date" class="form-control event_start_date search1" name="start_date1" id="start-date1" placeholder="Data" required>
                            </div>
                        <strong><p id="currentCell"></p></strong>
                        <label for="">Do której celi chcesz go przenieść?</label>
                        <select class="choose_cell">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <input type="submit" value="Przenieś" onclick="movePrisoner()" name="move" class="bg-dark text-light btn-prisoner">
                    </div>
                </div>
            </div>
       
    </header>

    <script src="./js/map.js"></script>



</body>
</html>