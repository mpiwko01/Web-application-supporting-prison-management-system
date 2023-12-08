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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="defaultContent()"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <span>Zmieniono hasło</span> 
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
                            <ul class="d-flex flex-lg-column my-2 m-lg-0 p-0">
                                <li class="list-group-item py-1 flex-grow-1"><form action="raport_generator.php" method="post" class="d-flex justify-content-start"><input type="submit" name="generuj_raport" class="btn-1 btn px-0 pt-0 pb-2" value="Raport ogólny"></form></li>
                                <li class="list-group-item py-1 flex-grow-1 d-flex justify-content-center justify-content-lg-start"><input type="button" value="Zmień hasło" class="btn-1 btn px-0 pt-0 pb-2 password-button"></li>
                                <li class="list-group-item py-1 flex-grow-1 d-flex justify-content-center justify-content-lg-start">
                                    <div class="wrapper">
                                        <button class="px-0 pt-0 pb-2 employee-button" <?php if ($_SESSION['position'] === 'pracownik') echo 'disabled'; ?>>Dodaj pracownika</button>
                                        <div class="tooltip">Brak uprawnień.</div>
                                    </div>
                                </li>
                                <li class="list-group-item py-1 flex-grow-1"><form action="wylogowanie.php" method="post" class="d-flex justify-content-end justify-content-lg-start"><input type="submit" value="Wyloguj się" name="wyloguj" class="btn-4 btn px-0 pt-0 pb-2"></form></li>
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
