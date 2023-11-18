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
        <div class="container main_box py-5">
            <div class="d-flex flex-column justify-content-center align-items-center left_box">
                <h1>MAPA WIĘZIENIA</h1>
                <div class="d-flex justify-content-center mb-3">
                    <button class="floor" id="floorButton">Piętro 1 <i class="fas fa-chevron-right"></i></button>
                </div>
                <button id="move" class="btn d-none move my-3 text-light bg-dark " onclick="movePopup()">PRZENIEŚ WIĘŹNIA</button>
                <strong><p class="mb-0 text-center">Lista nieprzypisanych więźniów:</p></strong>
                <span class="prisoner-list text-center m-3"></span>
                <a class="btn bg-dark text-light mb-3 relations" href="#relations">Zobacz powiązania więźniów</a>



            </div>
            
            <div class="d-flex flex-column justify-content-center align-items-center">
                <strong class="mb-3"><span class="floor_number"></span></strong>

                <div class="div-boxes">
                
                    <div class="prison_cell col-12 col-md-4 col-lg-3" id="1">
                        <h3 class="nr_celi" >CELA NR 1</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <div class="space_for_prisoners"></div>

                        <button id="btn-1" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                            
                        
                        
                    </div>
                    <div class="prison_cell col-12 col-md-4 col-lg-3" id="2">
                        <h3 class="nr_celi" >CELA NR 2</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-2" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
            
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3" id="3">
                        <h3 class="nr_celi" >CELA NR 3</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-3" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3" id="4">
                        <h3 class="nr_celi" >CELA NR 4</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-4" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3" id="5">
                        <h3 class="nr_celi" >CELA NR 5</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-5" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                    
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3" id="6">
                        <h3 class="nr_celi" >CELA NR 6</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-6" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 d-none" id="7">
                        <h3 class="nr_celi" >CELA NR 7</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-7" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 d-none"  id="8">
                        <h3 class="nr_celi">CELA NR 8</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-8" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 d-none"  id="9">
                        <h3 class="nr_celi">CELA NR 9</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-9" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 d-none" id="10">
                        <h3 class="nr_celi" >CELA NR 10</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-10" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 d-none" id="11">
                        <h3 class="nr_celi" >CELA NR 11</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-11" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 d-none" id="12">
                        <h3 class="nr_celi" >CELA NR 12</h3>
                        <strong><p class="mb-0 list_of"></p></strong>
                        <div class="space_for_prisoners"></div>
                        <button id="btn-12" class="btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        
                    </div>

                </div>
            </div>
            
            



            
            <div id="popup" class="pop" style="display: none;">
                <div class="popup-content">
                    <div class="info">
                        <h3 class=" text-center">WYSZUKAJ WIĘŹNIA</h3>
                        <button type="button" class="btn-close" onclick="closePopup('popup')"></button>
                    </div>

                
                    <div class="dropdown">
                        <label>Dodaj więźnia</label>
                        <input type="text" name="search_box" class="form-control form-control-lg search" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data(this.value)" required />
                        <span id="search_result"></span>
                        <div class="form-group mt-3">

                            <label for="start-date">Data<span class="text-danger">*</span></label>
                            <input type="date" class="form-control event_start_date search" name="start_date" id="start-date" placeholder="Data" required>
                        </div>
                        <div style="display: flex; justify-content: end;">
                            <button type="submit" onclick="addPrisoner()" name="dodaj" class="btn btn-add bg-dark text-light btn-prisoner mt-3">Dodaj</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="popup1" class="move-popup"  style="display:none">
                <div class="popup-content1">
                    <div class="info">
                        <h3 class="text-center">PRZENIEŚ WIĘŹNIA</h3>
                        <button type="button" class="btn btn-close" onclick="closePopup('popup1')"></button>
                    </div>
            
                    <div class="dropdown">
                        <label>Którego więźnia chcesz przenieść?</label>
                        <input type="text" name="search_box1" class="form-control form-control-lg move-search" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data2(this.value)" required />
                        <span id="search_result1"></span>
                        <div class="form-group mt-3">
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
                        <div style="display: flex; justify-content: end;">
                            <button type="submit" onclick="movePrisoner()" name="move" class="btn bg-dark text-light btn-prisoner mt-3">Przenieś</button>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
        <div class="container">
            <section id="relations">
                <div class="table d-none">
                    <table class="my-table">
                        <tr>
                            <th class="number">Nr</th>
                            <th class="prisoner_id">ID</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                        </tr>
                                <?php

                                include 'select_all.php';
                                
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='id_data'>" . $row['prisoner_id'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['surname'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                    </table>
                </div>
            </section>
        </div>

            <!--POWIĄZANIA-->
            <div class="popup_relations d-none" id="relations_popup">
                <div class="content d-flex justify-content-between">
                    <h3>Powiązania więźnia: <span class="for_name"></span></h3>
                    <button type="button" class="btn btn-close" onclick="closePopup('relations_popup')"></button>
                </div>
                <div class="results">
                    
                </div>
               
                

            </div>





        </div>
    </header>

    <script src="./js/map.js"></script>



</body>
</html>