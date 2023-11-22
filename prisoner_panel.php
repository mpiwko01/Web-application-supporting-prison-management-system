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
                <h1 class="text-center pb-5 d-none">WYSZUKIWARKA WIĘŹNIÓW</h1>
                <div class="dropdown pb-3">
                    <input type="text" name="search_box" id="search" class="form-control form-control-lg" placeholder="Wpisz imię i nazwisko szukanego więźnia" onkeyup="javascript:load_data(this.value)" />
		            <span id="search_result"></span>
                </div>
                <div class="buttons">
                    <button id="table-btn"class="btn-add bg-dark text-light mb-3" >Wyświetl wszystko</button>
                    <button onclick="addPrisonerContent(1); addPopup()" id="add_prisoner"class="btn-add bg-dark text-light mb-3">Dodaj więźnia do systemu</button>
                </div>
                <div class="image-holder">
                    <img src="./img/homepage_image.png" alt="">
                </div>
                <div id="popup" class="add-popup popup mb-3" style="display: none">
                    <div class="popup-content position-relative">
                        <div class="info">
                            <h3 class="pb-3 text-center add-label"></h3>
                            <button type="button" class="btn-close" onclick="closeAddPopup()"></button>
                        </div>
                        <div class="form-group row">
                            <h5>Dane:</h3>
                            <div class="col-md-6">
                                <label for="name_input">Imię:</label>
                                <input type="text" class="form-control" id="name_input" name="name_input" placeholder="Imię">
                                <span class="error-message" id="name-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="surname_input">Nazwisko:</label>
                                <input type="text" class="form-control" id="surname_input" name="surname_input" placeholder="Nazwisko">
                                <span class="error-message" id="surname-error"></span>
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
                                <span class="error-message" id="birth_date-error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                        <h5 class="pt-3">Adres zameldowania:</h3>
                            <div class="col-md-6">
                                <label for="street_input">Ulica:</label>
                                <input type="text" class="form-control" id="street_input" name="street_input" placeholder="Ulica">
                                <span class="error-message" id="street-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="house_number_input">Numer domu/mieszkania:</label>
                                <input type="text" class="form-control" id="house_number_input" name="house_number_input" placeholder="Numer domu/mieszkania">
                                <span class="error-message" id="house_number-error"></span>
                            </div> 
                        </div>
        
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="city_input">Miasto:</label>
                                <input type="text" class="form-control" id="city_input" name="city_input" placeholder="Miasto">
                                <span class="error-message" id="city-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="zip_code_input">Kod pocztowy:</label>
                                <input type="text" class="form-control" id="zip_code_input" name="zip_code_input" placeholder="Kod pocztowy">
                                <span class="error-message" id="zip_code-error"></span>
                            </div>
                        </div>
                        
                            
                        <div class="form-group row">
                            <h5 class="pt-3">Szczegóły dotyczące wyroku:</h5>
                            <div class="col-md-6">
                                <label for="start_date_input">Data początkowa wyroku:</label>
                                <input type="date" class="form-control" id="start_date_input" name="start_date_input" required>
                                <span class="error-message" id="start_date-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date_input">Data końcowa wyroku:</label>
                                <input type="date" class="form-control" id="end_date_input" name="end_date_input" required>
                                <span class="error-message" id="end_date-error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="crime_input ">Czyn zabroniony:</label>
                            <select class="form-control crime_input" id="crime_input" name="crime_input">
                                <option value="1">Kradzież z włamaniem</option>
                                <option value="2">Zabójstwo</option>
                                <option value="3">Przestępstwo gospodarcze</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-add bg-dark text-light add-button">
                    </div>
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
                <div  class="prisoner-popup popup d-none" id="prisoner-popup">
                    <div class="popup-content">

                        <div class="container-info d-flex row">
                            <div class="personal-info col-6">
                                <div class="data">
                                    <span><b>Dane osobowe:</b></span>
                                    <span class="d-flex"><span class="me-2">Imię:</span><span class="info space_name"></span></span>
                                    <span class="d-flex"><span class="me-2">Nazwisko:</span><span class="info space_surname"></span></span>
                                    <span class="d-flex"><span class="me-2">Płeć:</span><span class="info space_sex"></span></span>
                                    <span class="d-flex"><span class="me-2">Data urodzenia:</span><span class="info space_birth_date"></span></span>
                                    <span class="d-flex"><span class="me-2">Wiek:</span><span class="info space_age"></span></span>
                                </div>
                            </div>
                            <div class="jpg-box col-6">
                                <img class="prisoner_jpg" src="" alt="">
                            </div>
                            <button type="button" class="btn-close" onclick="togglePopup('prisoner-popup'); clearButtonBox()"></button>
                        </div>
                        
                        <div class="container-info d-flex row">
                            <div class="address-info col-6">
                                <div class="data">
                                    <span><b>Dane adresowe:</b></span>
                                    <span class="d-flex"><span class="me-2">Ulica:</span><span class="info space_street"></span></span>
                                    <span class="d-flex"><span class="me-2">Numer domu/mieszkania:</span><span class="info space_house_number"></span></span>
                                    <span class="d-flex"><span class="me-2">Miasto:</span><span class="info space_city"></span></span>
                                    <span class="d-flex"><span class="me-2">Kod pocztowy:</span><span class="info space_zip_code"></span></span>
                                </div>
                            </div>
                            <div class="sentence-info col-6">
                                <div class="data">
                                    <span><b>Dane wyroku:</b></span>
                                    <span class="d-flex"><span class="me-2">Czyn zabroniony:</span><span class="info space_crime"></span></span>
                                    <span class="d-flex"><span class="me-2">Data poczakowa wyroku:</span><span class="info space_start_date"></span></span>
                                    <span class="d-flex"><span class="me-2">Data końcowa wyroku:</span><span class="info space_end_date"></span></span>
                                    <span class="d-none release"><span class="me-2">Data opuszczenia więzienia:</span><span class="info space_release_date"></span></span>
                                    <span class="d-none days"><span class="me-2">Pozostałe dni:</span><span class="info space_days"></span></span>
                                </div>      
                            </div>
                        </div>
                        
                        <div class="container-info d-flex row">
                            <div class="more-info">
                                <div class="data">
                                    <span><b>Inne dane:</b></span>
                                    <span class="d-flex"><span class="me-2">Obecna cela:</span><span class="space_cell"></span></span>
                                    <span>Historia:</span>
                                    <span>Parametry więźnia:</span>
                                </div>   
                            </div>
                        </div>
                        
                        <div class="button-box d-flex justify-content-center"></div>
                    
                    </div>
                </div>

                <div id="alert-popup" class="alert-popup" style="display: none">
                    <div class="popup-content1"></div>
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
                                    <option value="3">Przestępstwo gospodarcze</option>
                                </select>
                            </div>
                            <input type="submit" class="btn btn-add bg-dark text-light mt-2" value="Dodaj" onclick="addReoffender()">
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