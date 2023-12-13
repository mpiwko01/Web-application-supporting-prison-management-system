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
    <link rel="stylesheet" href="map.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a6f2b46177.js" crossorigin="anonymous"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 sticky-top">
        <div class="container ">
            <a class="navbar-brand" href="prisoner_panel.php"><i class="fa-solid fa-magnifying-glass"></i><strong>CellBlock</strong> <em>Manager</em></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto text-uppercase">
                    <a class="nav-link px-lg-3" href="../prisoner_panel/prisoner_panel.php">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="../calendar/calendar.php">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="../employee_panel/panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <div class="container main_box py-5">
            <div class="d-flex flex-column justify-content-center align-items-center left_box">
                <h1>PLAN WIĘZIENIA</h1>
                <div class="d-flex d-lg-block justify-content-center text-center mb-3">
                    <div class="d-flex">
                        <button class="floor mt-2 mx-1" id="floorButton1">Piętro 1</button>
                        <button class="floor mt-2 mx-1" id="floorButton2">Piętro 2</button>
                    </div>
                    <div class="d-flex">
                        <button class="floor mt-2 mx-1" id="floorButton3">Piętro 3</button>
                        <button class="floor mt-2 mx-1" id="floorButton4">Piętro 4</button>
                    </div> 
                </div>
                <button id="move" class="btn move my-3 text-light bg-dark">PRZENIEŚ WIĘŹNIA</button>
                <strong><p class="mb-0 text-center">Lista nieprzypisanych więźniów:</p></strong>
                <span class="prisoner-list text-center m-3"></span>
                <a class="btn bg-dark text-light mb-3 relations" href="#relations">Zobacz powiązania więźniów</a>
            </div>
            
            <div class="d-flex flex-column justify-content-center important_box align-items-center">
                <strong class="mb-3"><span class="floor_number"></span></strong>

                <div class="div-boxes">
                
                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative" id="1">
                        <h3 class="nr_celi" >CELA NR 1</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-1" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" class="progress_bar" style="--max: 100;"></progress>
                        <div class="question-mark-icon more-info" id="1">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative" id="2">
                        <h3 class="nr_celi" >CELA NR 2</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-2" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" class="progress_bar" max="100" style="--value: 0; --max: 100;"></progress>
                        <div class="question-mark-icon more-info" id="2">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative" id="3">
                        <h3 class="nr_celi" >CELA NR 3</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-3" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="3">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative" id="4">
                        <h3 class="nr_celi" >CELA NR 4</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-4" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="4">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative" id="5">
                        <h3 class="nr_celi" >CELA NR 5</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-5" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="5">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative" id="6">
                        <h3 class="nr_celi" >CELA NR 6</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-6" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="6">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative d-none" id="7">
                        <h3 class="nr_celi" >CELA NR 7</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-7" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="7">
                            
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative d-none" id="8">
                        <h3 class="nr_celi" >CELA NR 8</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-8" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress class="progress_bar" value="" max="100" style="--value: 0; --max: 100;"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="8">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative d-none" id="9">
                        <h3 class="nr_celi" >CELA NR 9</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-9" class=" btn btn-add bg-dark text-light my-3" >DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="9">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative d-none" id="10">
                        <h3 class="nr_celi" >CELA NR 10</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-10" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="10">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative d-none" id="11">
                        <h3 class="nr_celi" >CELA NR 11</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-11" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="11">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-4 col-lg-3 position-relative d-none" id="12">
                        <h3 class="nr_celi" >CELA NR 12</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-12" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="12">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-6 col-lg-3 position-relative d-none" id="13">
                        <h3 class="nr_celi" >CELA NR 13</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-13" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="13">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-6 col-lg-3 position-relative d-none" id="14">
                        <h3 class="nr_celi" >CELA NR 14</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-14" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="14">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-6 col-lg-3 position-relative d-none" id="15">
                        <h3 class="nr_celi" >CELA NR 15</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-11" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="15">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-6 col-lg-3 position-relative d-none" id="16">
                        <h3 class="nr_celi" >CELA NR 16</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-16" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="16">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-6 col-lg-3 position-relative d-none" id="17">
                        <h3 class="nr_celi" >CELA NR 17</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-17" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="17">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="prison_cell col-12 col-md-6 col-lg-3 position-relative d-none" id="18">
                        <h3 class="nr_celi" >CELA NR 18</h3>
                        <strong><p class="mb-0  list_of"></p></strong>
                        <span class="space space_for_prisoners"></span>
                        <button id="btn-18" class=" btn btn-add bg-dark text-light my-3">DODAJ WIĘŹNIA</button>
                        <progress value="" max="100" style="--value: 0; --max: 100;" class="progress_bar"><span class="quantity"></span></progress>
                        <div class="question-mark-icon more-info" id="18">
                            <i class="fas fa-question-circle"></i>
                            <div class="question-mark-content">
                                <p class="mb-3  list_of empty_list"></p>
                                <span class="space space_for_info"></span>
                            </div>
                        </div> 
                    </div>

                </div>
            </div>
            
            <div id="popup" class="popup-add modal fade mb-3">
                <div class="modal-dialog" role="document">
                    <div class="modal-content popup-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title add-label" id="modal-title">Dodaj więźnia do celi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="dropdown">
                                <input type="text" name="search_box" class="form-control form-control-lg search data" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data(this.value)" required />
                                <span id="search_result"></span>
                                <div class="form-group mt-3">
                                    <label for="start-date">Data<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control event_start_date search data" name="start_date" id="start-date" placeholder="Data" required>
                                </div>
                                <div style="display: flex; justify-content: end;">
                                    <button type="submit"  name="dodaj" class="btn bg-dark text-light btn-prisoner-add mt-3">Dodaj</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>




            <div id="popup1" class="modal fade popup-move">
                <div class="modal-dialog" role="document">
                    <div class="modal-content popup-content1">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title add-label" id="modal-title">Przenieś więźnia</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="model-body">
                            <div class="dropdown">
                                <input type="text" name="search_box1" class="form-control form-control-lg move-search data" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data2(this.value)" required />
                                <span id="search_result1"></span>
                                <div class="form-group mt-3">
                                        <label for="start-date1">Data<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control event_start_date search1 data" name="start_date1" id="start-date1" placeholder="Data" required>
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
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                </select>
                                <div style="display: flex; justify-content: end;">
                                    <button type="submit" name="move" class="btn bg-dark text-light btn-prisoner-move  mt-3">Przenieś</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal fade message-popup">
                <div class="modal-dialog modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title add-label" id="modal-title">KOMUNIKAT:</h5>
                            <span class="modal-title message" id="modal-title"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body message-long">
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
        <div class="modal fade popup_relations">
            <div class="modal-dialog" role="document">
                <div class="modal-content content d-flex justify-content-between">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title add-label" id="modal-title">Powiązania więźnia: <span class="selected_prisoner"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="results"></div>
                    </div>
                </div>
            </div>
        </div>

    </header>
    
    <script src="map.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    
</body>
</html>