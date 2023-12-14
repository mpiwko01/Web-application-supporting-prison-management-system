<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']!==1))
{
    
    header('Location: ../logpage/logpage.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web prison management system - Home page</title>
    <link rel="stylesheet" href="prisoner.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a6f2b46177.js" crossorigin="anonymous"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 sticky-top">
        <div class="container ">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-magnifying-glass"></i><strong>CellBlock</strong> <em>Manager</em></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto text-uppercase">
                    <a class="nav-link px-lg-3" href="prisoner_panel.php">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="../calendar/calendar.php">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="../map/map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="../employee_panel/panel.php">Konto</a>
                </div>
            </div>
       
        </nav>

        <header>
            <div class="container py-5 box">
                <div class="main_menu">
                    <a id="table-current-btn" class="btn btn-add bg-dark text-light mb-3" 
    href="prisoner_archive.php">Przejdź do archiwum więźniów</a>
                    <h2 class="text-center pb-2">WYSZUKIWARKA WIĘŹNIÓW</h2>
                </div>
                
                <div class="dropdown pb-3">
                    <input type="text" name="search_box" id="search" class="form-control form-control-lg" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data(this.value)" />
		            <span id="search_result"></span>
                </div>
                <div class="buttons">
                    <button id="table-btn"class="btn btn-add bg-dark text-light mb-3" >Wyświetl wszystko</button>
                    <div class="wrapper">
                        <button id="add_prisoner" class="btn btn-add bg-dark text-light mb-1" <?php if ($_SESSION['position'] === 'pracownik') echo 'disabled'; ?>>Dodaj więźnia do systemu</button>
                        <div class="tooltip">Brak uprawnień.</div>
                    </div>  
                </div>
                <div class="image-holder">
                    <img src="homepage_image.png" alt="">
                </div>
                

                <div class="modal fade delete-popup">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <span class="modal-title popup-content1" id="modal-title"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <button class="btn btn-add bg-dark text-light mx-2 delete-prisoner" type="submit">Usuń</button>
                                <button class="btn btn-add bg-dark text-light mx-2 cancel-button" type="button">Anuluj</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade message-popup">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <span class="modal-title message" id="modal-title"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>

                

                <div id="popup" class="modal fade add-popup popup mb-3">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title add-label" id="modal-title">Dodaj więźnia do systemu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <h5>Dane:</h5>
                                    <div class="col-md-6">
                                        <label for="name_input">Imię:</label>
                                        <input type="text" class="form-control" id="name_input" name="name_input" placeholder="Imię">
                                        <span class="error-message error" id="name-error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="surname_input">Nazwisko:</label>
                                        <input type="text" class="form-control" id="surname_input" name="surname_input" placeholder="Nazwisko">
                                        <span class="error-message error" id="surname-error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="sex_input">Płeć:</label>
                                        <select class="form-control sex_input" id="sex_input" name="sex_input">
                                            <option value="F">Kobieta</option>
                                            <option value="M">Mężczyzna</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birth_date_input">Data urodzenia:</label>
                                        <input type="date" class="form-control" id="birth_date_input" name="birth_date_input" required>
                                        <span class="error-message error" id="birth_date-error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-12">
                                        <label for="pesel_input">PESEL:</label>
                                        <input type="text" class="form-control" id="pesel_input" name="pesel_input" placeholder="PESEL" required>
                                        <span class="error-message error" id="pesel-error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label class="form-label mb-0" for="customFile">Dodaj zdjęcie:</label>
                                        <input type="file" class="form-control file_input" id="file_input" name="file_input" />
                                        <span class="error-message error" id="file-error"></span>
                                    </div>
                                </div>
                                <div id="preview"></div>
                                <div class="form-group row">
                                    <h5 class="pt-3">Adres zameldowania:</h3>
                                    <div class="col-md-6">
                                        <label for="street_input">Ulica:</label>
                                        <input type="text" class="form-control" id="street_input" name="street_input" placeholder="Ulica">
                                        <span class="error-message error" id="street-error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="house_number_input">Numer domu/mieszkania:</label>
                                        <input type="text" class="form-control" id="house_number_input" name="house_number_input" placeholder="Numer domu/mieszkania">
                                        <span class="error-message error" id="house_number-error"></span>
                                    </div> 
                                </div>
            
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="city_input">Miasto:</label>
                                        <input type="text" class="form-control" id="city_input" name="city_input" placeholder="Miasto">
                                        <span class="error-message error" id="city-error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="zip_code_input">Kod pocztowy:</label>
                                        <input type="text" class="form-control" id="zip_code_input" name="zip_code_input" placeholder="Kod pocztowy">
                                        <span class="error-message error" id="zip_code-error"></span>
                                    </div>
                                </div> 
                                <div class="form-group row">
                                    <h5 class="pt-3">Szczegóły dotyczące wyroku:</h5>
                                    <div class="col-md-6">
                                        <label for="start_date_input">Data początkowa wyroku:</label>
                                        <input type="date" class="form-control" id="start_date_input" name="start_date_input" required>
                                        <span class="error-message error" id="start_date-error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date_input">Data końcowa wyroku:</label>
                                        <input type="date" class="form-control" id="end_date_input" name="end_date_input" required>
                                        <span class="error-message error" id="end_date-error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="crime_input ">Czyn zabroniony:</label>
                                    <select class="form-control crime_input" id="crime_input" name="crime_input">
                                        <option value="1">Kradzież z włamaniem</option>
                                        <option value="2">Zabójstwo</option>
                                        <option value="3">Przywłaszczenie</option>
                                        <option value="4">Fałszowanie pieniędzy</option>
                                        <option value="5">Rozbój z użyciem niebezpiecznego narzędzia</option>
                                        <option value="6">Groźba karalna</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-add bg-dark text-light add-button mt-2">Dodaj</button>
                                </div>
                                
                            </div>
                        </div>  
                    </div>
                </div>

                <div id="popup" class="modal fade edit-popup popup mb-3">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title add-label" id="modal-title">Edytuj dane więźnia</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <h5>Dane:</h3>
                                    <div class="box row">
                                        <div class="prisoner col-md-6">
                                            <div>
                                                <label for="name_input_edit">Imię:</label>
                                                <input type="text" class="form-control" id="name_input_edit" name="name_input_edit" placeholder="Imię">
                                                <span class="error-message error" id="name-error-edit"></span>
                                            </div>
                                            <div>
                                                <label for="surname_input_edit">Nazwisko:</label>
                                                <input type="text" class="form-control" id="surname_input_edit" name="surname_input_edit" placeholder="Nazwisko">
                                                <span class="error-message error" id="surname-error-edit"></span>
                                            </div>
                                            <div>
                                                <label for="sex_input_edit">Płeć:</label>
                                                <select class="form-control sex_input sex_input_edit" id="sex_input" name="sex_input_edit">
                                                    <option value="F">Kobieta</option>
                                                    <option value="M">Mężczyzna</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6" style=" margin: 0 auto; padding: 0;">
                                            <div class="photo_current">
                                                <img class="prisoner_jpg_current" src="" alt="">
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="birth_date_input_edit">Data urodzenia:</label>
                                        <input type="date" class="form-control" id="birth_date_input_edit" name="birth_date_input_edit" required>
                                        <span class="error-message error" id="birth_date-error-edit"></span>
                                    </div>
                                    <div class="col-md-6 d-grid align-items-end">
                                        <button type="button" class="btn bg-dark text-light btn-change">Zmień zdjęcie</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-12">
                                        <label for="pesel_input_edit">PESEL:</label>
                                        <input type="text" class="form-control" id="pesel_input_edit" name="pesel_input_edit" required>
                                        <span class="error-message error" id="pesel-error-edit"></span>
                                    </div>
                                </div>
                                <div class="form-group row add-image d-none">
                                    <div class="col-12">
                                        <label class="form-label mb-0" for="customFile">Dodaj zdjęcie:</label>
                                        <input type="file" class="form-control file_input" id="file_input_edit" name="file_input_edit" />
                                        <span class="error-message error" id="file-error-edit"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <h5 class="pt-3">Adres zameldowania:</h3>
                                    <div class="col-md-6">
                                        <label for="street_input_edit">Ulica:</label>
                                        <input type="text" class="form-control" id="street_input_edit" name="street_input_edit" placeholder="Ulica">
                                        <span class="error-message error" id="street-error-edit"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="house_number_input_edit">Numer domu/mieszkania:</label>
                                        <input type="text" class="form-control" id="house_number_input_edit" name="house_number_input_edit" placeholder="Numer domu/mieszkania">
                                        <span class="error-message error" id="house_number-error-edit"></span>
                                    </div> 
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="city_input">Miasto:</label>
                                        <input type="text" class="form-control" id="city_input_edit" name="city_input_edit" placeholder="Miasto">
                                        <span class="error-message error" id="city-error-edit"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="zip_code_input">Kod pocztowy:</label>
                                        <input type="text" class="form-control" id="zip_code_input_edit" name="zip_code_input_edit" placeholder="Kod pocztowy">
                                        <span class="error-message error" id="zip_code-error-edit"></span>
                                    </div>
                                </div> 
                                <div class="form-group row">
                                    <h5 class="pt-3">Szczegóły dotyczące wyroku:</h5>
                                    <div class="col-md-6">
                                        <label for="start_date_input">Data początkowa wyroku:</label>
                                        <input type="date" class="form-control" id="start_date_input_edit" name="start_date_input_edit" required>
                                        <span class="error-message error" id="start_date-error-edit"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date_input_edit">Data końcowa wyroku:</label>
                                        <input type="date" class="form-control" id="end_date_input_edit" name="end_date_input_edit" required>
                                        <span class="error-message error" id="end_date-error-edit"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="crime_input_edit">Czyn zabroniony:</label>
                                    <select class="form-control crime_input crime_input_edit" id="crime_input_edit" name="crime_input_edit">
                                        <option value="1">Kradzież z włamaniem</option>
                                        <option value="2">Zabójstwo</option>
                                        <option value="3">Przywłaszczenie</option>
                                        <option value="4">Fałszowanie pieniędzy</option>
                                        <option value="5">Rozbój z użyciem niebezpiecznego narzędzia</option>
                                        <option value="6">Groźba karalna</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-add bg-dark text-light edit-button mt-2">Zapisz zmiany</button>
                                </div>
                                
                            </div>
                        </div>  
                    </div>
                </div>
                    
                <div class="table d-none">
                    <table class="my-table">
                        <tr class="all_tr">
                            <th class="number">Nr</th>
                            <th class="prisoner_id">ID</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Płeć</th>
                            <th>Data urodzenia</th>
                        </tr>
                        <?php

                        include 'select_current.php';
                        
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
                <div  class="modal fade prisoner_modul" id="prisoner-popup">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header different_header border-bottom-0">
                                <h5 class="modal-title" id="modal-title">Informacje</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="special_information">

                            </div>
                            <div class="modal-body">
                                <div class="data d-flex flex-row justify-content-between ">
                                    <div class="personal">
                                        <span><b>Dane osobowe:</b></span>
                                        <span class="d-flex"><span class="me-2">Imię:</span><span class="info space_name"></span></span>
                                        <span class="d-flex"><span class="me-2">Nazwisko:</span><span class="info space_surname"></span></span>
                                        <span class="d-flex"><span class="me-2">Płeć:</span><span class="info space_sex"></span></span>
                                        <span class="d-flex"><span class="me-2">Data urodzenia:</span><span class="info space_birth_date"></span></span>
                                        <span class="d-flex"><span class="me-2">PESEL:</span><span class="info space_pesel"></span></span>
                                        <span class="d-flex"><span class="me-2">Wiek:</span><span class="info space_age"></span></span>
                                    </div>
                                    <div class="photo">
                                        <img class="prisoner_jpg" src="" alt="">
                                    </div>
                                </div>
                                <div class="data">
                                    <span><b>Dane adresowe:</b></span>
                                    <span class="d-flex"><span class="me-2">Ulica:</span><span class="info space_street"></span></span>
                                    <span class="d-flex"><span class="me-2">Numer domu/mieszkania:</span><span class="info space_house_number"></span></span>
                                    <span class="d-flex"><span class="me-2">Miasto:</span><span class="info space_city"></span></span>
                                    <span class="d-flex"><span class="me-2">Kod pocztowy:</span><span class="info space_zip_code"></span></span>
                                </div>
                                <div class="data">
                                    <span><b>Dane wyroku:</b></span>
                                    <span class="d-flex"><span class="me-2">Czyn zabroniony:</span><span class="info space_crime"></span></span>
                                    <span class="d-flex"><span class="me-2">Data początkowa wyroku:</span><span class="info space_start_date"></span></span>
                                    <span class="d-flex"><span class="me-2">Data końcowa wyroku:</span><span class="info space_end_date"></span></span>
                                    <span class="d-none release"><span class="me-2">Data opuszczenia więzienia:</span><span class="info space_release_date"></span></span>
                                    <span class="d-none days"><span class="me-2">Pozostałe dni:</span><span class="info space_days"></span></span>
                                </div> 
                                <div class="data">
                                    <span class="d-flex other"><b>Inne dane:</b></span>
                                    <span class="d-flex cell"><span class="me-2">Obecna cela:</span><span class="space_cell"></span></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="button-box d-flex justify-content-space-between"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade reoffender-popup mb-3">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content popup-content2">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title add-label" id="modal-title">Dodaj nowy wyrok</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <h5 class="pt-3">Szczegóły dotyczące wyroku:</h5>
                                    <div class="col-md-6">
                                        <label for="start_date_input">Data początkowa wyroku:</label>
                                        <input type="date" class="form-control" id="start_date_input_reoffender" name="start_date_input_reoffender" required>
                                        <span class="error-message error" id="start_date-error-reoffender"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date_input">Data końcowa wyroku:</label>
                                        <input type="date" class="form-control" id="end_date_input_reoffender" name="end_date_input_reoffender" required>
                                        <span class="error-message error" id="end_date-error-reoffender"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="crime_input ">Czyn zabroniony:</label>
                                    <select class="form-control crime_input_reoffender" id="crime_input_reoffender" name="crime_input_reoffender">
                                        <option value="1">Kradzież z włamaniem</option>
                                        <option value="2">Zabójstwo</option>
                                        <option value="3">Przywłaszczenie</option>
                                        <option value="4">Fałszowanie pieniędzy</option>
                                        <option value="5">Rozbój z użyciem niebezpiecznego narzędzia</option>
                                        <option value="6">Groźba karalna</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-add bg-dark text-light mt-2 reoffender-submit">Dodaj</button>
                            </div>         
                        </div>  
                    </div>
                </div>


                 <!-- MODUŁ RECYDYWISTA -->
                <div id="reoffender-popup" class="reoffender-popup" style="display: none">
                    <div class="popup-content2">
                        <div class="info">
                            <h3 class="pb-3 text-center">Dodaj nowy wyrok</h3>
                            <button type="button" class="btn-close" onclick="closeReoffenderPopup(); clearButtonBox()"></button>
                        </div>
                        <div>
                            <div class="form-group row">
                                <h5 class="pt-3">Szczegóły dotyczące wyroku:</h5>
                                <div class="col-md-6">
                                    <label for="start_date_input">Data początkowa wyroku:</label>
                                    <input type="date" class="form-control" id="start_date_input_reoffender" name="start_date_input_reoffender" required>
                                    <span class="error-message" id="start_date-error-reoffender"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date_input">Data końcowa wyroku:</label>
                                    <input type="date" class="form-control" id="end_date_input_reoffender" name="end_date_input_reoffender" required>
                                    <span class="error-message" id="end_date-error-reoffender"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="crime_input ">Czyn zabroniony:</label>
                                <select class="form-control crime_input_reoffender" id="crime_input_reoffender" name="crime_input_reoffender">
                                        <option value="1">Kradzież z włamaniem</option>
                                        <option value="2">Zabójstwo</option>
                                        <option value="3">Przywłaszczenie</option>
                                        <option value="4">Fałszowanie pieniędzy</option>
                                        <option value="5">Rozbój z użyciem niebezpiecznego narzędzia</option>
                                        <option value="6">Groźba karalna</option>
                                </select>
                            </div>
                            <input type="submit" class="btn btn-add bg-dark text-light mt-2" value="Dodaj" onclick="addReoffender()">
                        </div>
                    </div>
                </div>

            </div>
    </header>
    <script src="prisoner.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    
</body>

</html> 