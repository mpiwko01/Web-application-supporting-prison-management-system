<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

        // Zapytanie o ostatnie ID więźnia
        $query_prisoner_id = "SELECT MAX(prisoner_id) AS last_prisoner_id FROM prisoners";
        $result_prisoner_id = mysqli_query($dbconn, $query_prisoner_id);

        if ($result_prisoner_id && $result_prisoner_id->num_rows > 0) {
            $row = $result_prisoner_id->fetch_assoc();
            $lastPrisonerID = $row["last_prisoner_id"];
        }
        else {
            $lastPrisonerID = 0;
        }
        $prisonerID = $lastPrisonerID + 1;

        $file = $_FILES['file'];

        $photoName = $_FILES['file']['name'];

        $searchValueParts = explode('.', $photoName);
        $name = $searchValueParts[0];
        $ext = $searchValueParts[1];

        $photoName = $prisonerID . "." . $ext; 

        $targetDirectory = 'uploads/';
        $targetFile = $targetDirectory . basename($photoName);
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
    
            $query = "INSERT INTO photos VALUES ('$prisonerID','$photoName')";
            $result = mysqli_query($dbconn, $query);
            
        }
        else {
            //echo "Błąd podczas przesyłania pliku.";
        }
    }
    else {
        //echo "Nieprawidłowy sposób dostępu.";
    }
}
?>

