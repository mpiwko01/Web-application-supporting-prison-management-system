<?php
session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==1))
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
    <title>Web prison management system</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a6f2b46177.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container d-flex flex-column">
        
        <span class="logo"><i class="fa-solid fa-magnifying-glass"></i><strong>CellBlock</strong> <em>Manager</em></span>
            
        <div class="row form-container">
            <div>
                <h1>Logowanie</h1>
                <form action="../general_php/logowanie.php" method="post">
                    <div class="form-group">
                        <label for="username">ID</label>
                        <input type="text" name="login" class="form-control id" id="username" placeholder="ID">
                        <p class="error-text"></p>
                        <?php
	                            if(isset($_SESSION['error-login'])){
                                echo $_SESSION['error-login'];
                                unset($_SESSION['error-login']);
                                }
                            ?>
                        
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" name="password" class="form-control password" id="password" placeholder="Hasło">
                        <p class="error-text"></p>
                        <?php
	                            if(isset($_SESSION['error-password'])){
                                echo $_SESSION['error-password'];
                                unset($_SESSION['error-password']);
                                }
                            ?>
                        
                    </div>

                    <div class="button-box">
                        <div class="button text-end">
                            <button type="button" class="clear-btn btn bg-dark text-light">Wyczyść</button>
                        </div>

                        <div class="button text-end">
                            <button type="submit" id="log-btn" class="submit-btn btn bg-dark text-light" name="zaloguj">Zaloguj</button>
                        </div>
                    </div>
                    <?php
	                    if(isset($_SESSION['error'])){
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        }
                    ?>
                    

                </form>
            </div>

        </div> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
            </script>

        <script src="script.js"></script>
</body>

</html>