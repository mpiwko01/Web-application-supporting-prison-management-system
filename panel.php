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
    <link rel="stylesheet" href="./style/panel.css">
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
                    <a class="nav-link px-lg-3" href="prisoner_panel.php">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="./calendar/calendar.php">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="panel.php">Konto</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="modal fade password_modal" id="password_modal">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Zmiana hasła</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="defaultContent()"></button>
                </div>
                <div class="modal-body"> 
                        <div class="form-group">
                            <label for="old_password">Wprowadź stare hasło <span class="text-danger">*</span></label>
                            <input name="old_password" type="password" id="old_password" class="form-control" required>
                            <span class="error error-password-old"></span>
                        </div>
                        <div class="form-group">
                            <label for="password1">Wpisz nowe hasło <span class="text-danger">*</span></label>
                            <input name="password1" type="password" id="password1" class="form-control" required><span class="error error-password1"></span>
                        </div>
                        <div class="form-group">
                            <label for="password2">Wpisz ponownie nowe hasło <span class="text-danger">*</span></label>
                            <input name="password2" type="password" id="password2" class="form-control" required><span class="error error-password2"></span>
                        </div>
                        <div class="modal-footer border-top-0 d-flex justify-content-center">
                            <button type="submit" class="btn bg-dark text-light edit-button mt-2" id="password_change" onclick="changePassword()" name="password_change">Zmień hasło</button>
                        </div>
                </div>
            </div>
      
        </div>
    </div>

    <div class="modal fade password_modal_com" id="password_modal_com">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <span class="modal-title message" id="modal-title">Zmieniono hasło</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="defaultContent()"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                </div>     
            </div>
        </div>
    </div>


    <div class="modal fade employee-popup mb-3">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title add-label" id="modal-title">Dodaj pracownika</h5>
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
                        <h5 class="pt-3">Dane kontaktowe:</h5>
                        <div class="col-md-6">
                            <label for="email_input">Email:</label>
                            <input  class="form-control" id="email_input" name="email_input" required>
                            <span class="error-message error" id="email-error"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number_input">Telefon:</label>
                            <input class="form-control" id="phone_number_input" name="phone_number_input" required>
                            <span class="error-message error" id="phone_number-error"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h5 class="pt-3">Dane zatrudnienia:</h5>
                        <div class="col-md-6">
                            <label for="position_input ">Stanowisko:</label>
                            <select class="form-control position_input" id="position_input" name="position_input">
                                <option value="pracownik">pracownik</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="hire_date_input">Data zatrudnienia:</label>
                            <input type="date" class="form-control" id="hire_date_input" name="hire_date_input" required>
                            <span class="error-message error" id="hire_date-error"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-add bg-dark text-light add-employee mt-2">Dodaj</button>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <div class="modal fade employee_modal_com" id="employee_modal_com">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <span class="modal-title message-employee" id="modal-title"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body d-flex justify-content-center"></div>    
            </div>
        </div>
    </div>

    <div class="modal fade delete-popup">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <span class="modal-title message-delete" id="modal-title"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-add bg-dark text-light mx-2 delete-submit" type="submit">Usuń</button>
                        <button class="btn btn-add bg-dark text-light mx-2 cancel-button" type="button">Anuluj</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade employee-list-popup mb-3">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title add-label" id="modal-title">Lista pracowników</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table">
                        <table class="my-table">
                            <tr class="all_tr">
                                <th class="number">Nr</th>
                                <th class="prisoner_id">ID</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>E-mail</th>
                                <th>Stanowisko</th>
                                <th>Data zatrudnienia</th>
                            </tr>
                            <?php

                            include 'select_employees.php';
                            
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='id_data'>" . $row['id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['surname'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['position'] . "</td>";
                                echo "<td>" . $row['hire_date'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
    </div>


    <div class="modal fade archive-list-popup mb-3">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title add-label" id="modal-title">Byli pracownicy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table table1">
                        <table class="my-table1">
                            <tr class="all_tr">
                                <th class="number">Nr</th>
                                <th class="prisoner_id">ID</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>Stanowisko</th>
                                <th>Zatrudniony od</th>
                                <th>Zatrudniony do</th>
                            </tr>
                            <?php

                            include 'select_employees_archive.php';
                            
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='id_data'>" . $row['id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['surname'] . "</td>";
                                echo "<td>" . $row['position'] . "</td>";
                                echo "<td>" . $row['hire_date'] . "</td>";
                                echo "<td>" . $row['end_date'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
    </div>


    <header>
        <div class="container py-5 box">
            <div class="panel mb-3">
                <div class="panel-content m-0">
                    <div class="id-box m-0 mb-lg-4 container">
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
                            <ul class="d-flex flex-lg-column flex-wrap gap-3 my-2 m-lg-0 p-0">
                                <li class="list-group-item py-1"><form action="raport_generator.php" method="post" class="d-flex justify-content-start"><input type="submit" name="generuj_raport" class="btn-1 btn px-0 pt-0 pb-2" value="Raport ogólny"></form></li>
                                <li class="list-group-item py-1 d-flex justify-content-center justify-content-lg-start"><input type="button" value="Zmień hasło" class="btn-1 btn px-0 pt-0 pb-2 password-button"></li>
                                <li class="list-group-item py-1 d-flex justify-content-center justify-content-lg-start"><button class="btn-1 btn px-0 pt-0 pb-2 employee-list-button">Lista pracowników</button></li>
                                <li class="list-group-item py-1 d-flex justify-content-center justify-content-lg-start"><button class="btn-1 btn px-0 pt-0 pb-2 archive-list-button">Archiwum</button></li>
                                <li class="list-group-item py-1 d-flex justify-content-center justify-content-lg-start">
                                    <div class="wrapper">
                                        <button class="px-0 pt-0 pb-2 employee-button" <?php if ($_SESSION['position'] === 'pracownik') echo 'disabled'; ?>>Dodaj pracownika</button>
                                        <div class="tooltip">Brak uprawnień.</div>
                                    </div>
                                </li>
                                <li class="list-group-item py-1"><form action="wylogowanie.php" method="post" class="d-flex justify-content-end justify-content-lg-start"><input type="submit" value="Wyloguj się" name="wyloguj" class="btn-4 btn px-0 pt-0 pb-2"></form></li>
                            </ul>
                        </div>

                        <div class="col-lg-10 col-md-12 m-0 p-0 main-content">

                            <div class="row mb-4">
                                <div class="info col-12 col-xl-8">
                                    <div class="info-label">Dane personalne:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Imię: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['name']; ?></div>
                                            <div class="label col-12  col-sm-6">Nazwisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['surname']; ?></div>
                                        </div>
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Data urodzenia: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['birthDate']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="info col-12 col-xl-8">
                                    <div class="info-label">Dane adresowe:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Ulica: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['street']; ?></div>
                                            <div class="label col-12  col-sm-6">Numer domu/mieszkania: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['houseNumber']; ?></div>
                                        </div>
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Miasto: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['city']; ?></div>
                                            <div class="label col-12 col-sm-6">Kod pocztowy: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['zipCode']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="info col-12 col-xl-8">
                                    <div class="info-label">Dane kontaktowe:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6 email">Email: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['email']; ?></div>
                                            <div class="label col-12  col-sm-6">Numer telefonu: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['number']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="info col-12 col-xl-8">
                                    <div class="info-label">Dane zatrudnienia:</div>
                                    <div class="data-box">
                                        <div class="data-row row d-md-flex">
                                            <div class="label col-12 col-sm-6">Data zatrudnienia: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['hireDate']; ?></div>
                                            <div class="label col-12  col-sm-6">Stanowisko: <?php if ($_SESSION['zalogowany'] == 1) echo $_SESSION['position']; ?></div>
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

    <script src="./js/panel.js"></script>

</body>

</html>
