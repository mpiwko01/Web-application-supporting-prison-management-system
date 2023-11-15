<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['prisonerId']) && !empty($_POST['prisonerId']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['surname']) && !empty($_POST['surname']) && isset($_POST['sex']) && !empty($_POST['sex']) && isset($_POST['birthDate']) && !empty($_POST['birthDate']) && isset($_POST['street']) && !empty($_POST['street'])&& isset($_POST['houseNumber']) && !empty($_POST['houseNumber'])&& isset($_POST['city']) && !empty($_POST['city']) && isset($_POST['zipCode']) && !empty($_POST['zipCode']) && isset($_POST['startDate']) && !empty($_POST['startDate']) && isset($_POST['endDate']) && !empty($_POST['endDate']) && isset($_POST['crime']) && !empty($_POST['crime'])) {

        $prisonerId = $_POST['prisonerId'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $sex = $_POST['sex'];
        $birthDate = $_POST['birthDate'];
        $street = $_POST['street'];
        $houseNumber = $_POST['houseNumber'];
        $zipCode = $_POST['zipCode'];
        $city = $_POST['city'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $crime = $_POST['crime'];

        $inPrison = '1';

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        $query_prisoners = "UPDATE `prisoners` SET `name` = '$name', `surname`='$surname', `sex`='$sex', `birth_date`='$birthDate', `street`='$street', `house_number`='$houseNumber', `city`='$city', `zip_code`='$zipCode' WHERE `prisoner_id`='$prisonerId'";

        $result_prisoners = mysqli_query($dbconn, $query_prisoners);

        $query_prisoner_sentence = "UPDATE `prisoner_sentence` SET `crime_id` = '$crime', `from_date`='$startDate', `to_date`='$endDate' WHERE `prisoner_id`='$prisonerId'";

        $result_prisoner_sentence = mysqli_query($dbconn, $query_prisoner_sentence);

        $cell_counter_query = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `prisoner_id` = '$prisonerId' AND `to_date` IS NULL"; //sprawdza wiezien jest w jakiejs celi

        $result_cell_counter = mysqli_query($dbconn, $cell_counter_query);

        $date = new DateTime();
        $format = 'Y-m-d'; 
        $curDate = $date->format($format); //dzisiejsza data (data zmiany danych)

        if($result_prisoners && $result_prisoner_sentence &&$result_cell_counter) {
            $row_cell_counter = mysqli_fetch_assoc($result_cell_counter);
            $count = $row_cell_counter['query_counter'];
            if ($count != 0) { //wiezien jest w jakiejs celi -> usuwamy go stamtad bo zmiana danych moze spowodowac ze wiezien juz nie bedzie pasowal do celi

                $query_cell_history = "UPDATE `cell_history` SET `to_date` = '$curDate' WHERE `prisoner_id`='$prisonerId' AND `to_date` IS NULL";
                $result_cell_history = mysqli_query($dbconn, $query_cell_history); //?
            }
            echo "Dane więźnia zostały zaktualizowane.";     
        }
    }  
}

header('Content-Type: application/json');
//echo json_encode($response);
?>