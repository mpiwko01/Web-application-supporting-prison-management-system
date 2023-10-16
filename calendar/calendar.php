<?php
session_start();

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.3.0/main.min.css">
    <link rel="stylesheet" href="calendar.css">

    <script src="calendar.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.7.0/locale/pl.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.2.0/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.2.0/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uuid@8.3.2/dist/umd/uuidv4.min.js"></script>
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
                    <a class="nav-link px-lg-3" href="../prisoner_panel.php">Wyszukaj więźnia</a>
                    <a class="nav-link px-lg-3" href="./calendar.html">Kalendarz odwiedzin</a>
                    <a class="nav-link px-lg-3" href="../map.php">Plan więzienia</a>
                    <a class="nav-link px-lg-3" href="../panel.php">Konto</a>

                </div>
            </div>
        </div>
    </nav>

    <!-- (A) PERIOD SELECTOR -->
    <div id='calendar'></div>

    <!-- Add modal -->

    <div class="modal fade edit-form event_entry_modal" id="form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Dodaj odwiedziny</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="save_event.php" method="post" id="myForm">
                    <div class="modal-body">
                        <div class="alert alert-danger " role="alert" id="danger-alert" style="display: none;">
                            End date should be greater than start date.
                        </div>
                        <div class="form-group">
                            <label for="event-title">Osoba odwiedzająca <span class="text-danger">*</span></label>
                            <input name="visitor" type="text" id="visitor" class="form-control visitor" 
                                 required>
                        </div>
                        <div class="form-group">
                            <label for="event-title">Imię i nazwisko więźnia <span class="text-danger">*</span></label>
                            <input name="prisoner" type="text" id="prisoner" class="form-control prisoner"
                                 required>
                        </div>
                        <div class="form-group">
                            <label for="event-title">Typ wizyty<span class="text-danger">*</span></label><br>
                            <input name="event_name" type="radio" id="family" value="Rodzina"
                                 checked>
                            <label for="family">Rodzina</label><br>
                            <input name="event_name" type="radio" id="friend" value="Znajomy"
                                 >
                            <label for="friend">Znajomy</label><br>    
                            <input name="event_name" type="radio" id="attorney" value="Prawnik"
                                 >
                            <label for="attorney">Prawnik</label><br>   
                            <input name="event_name" type="radio" id="other" value="Inne"
                                 >
                            <label for="attorney">Inne</label><br> 
                        </div>
                        <div class="form-group">
                            <label for="start-date">Data<span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control event_start_date" name="event_start_date" id="start-date"
                                placeholder="Data" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="end-date">Godzina zakończenia<span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end" class="form-control event_end_date" id="end"
                                placeholder="end-date" required>
                        </div>
                        <!--<div class="form-group">
                            <label for="event-color">Kolor</label>
                            <input type="color" class="form-control" name="color" id="event-color" value="#3788d8">
                        </div>-->
                    </div>
                    
                    <div class="modal-footer border-top-0 d-flex justify-content-center">
                        <input type="submit" class="btn btn-success dodaj" id="submit-button" name="dodaj" value="dodaj"></input>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-modal-title">Potwierdź usunięcie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="delete-modal-body">
                    Jesteś pewien, że chcesz usunąc odwiedziny?
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-sm" data-dismiss="modal"
                        id="cancel-button">Anuluj</button>
                    <button type="button" class="btn btn-danger rounded-lg" id="delete-button" name="delete" >Usuń</button>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>