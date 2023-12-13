<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        if (isset($_POST['prisonerId']) && !empty($_POST['prisonerId'])) {
            
            $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");

            $prisonerId = $_POST['prisonerId'];

            $targetDirectory = 'uploads/';
            $existingFiles = glob($targetDirectory . $prisonerId . ".*");

            foreach ($existingFiles as $file) unlink($file);
            
            $file = $_FILES['file'];

            $photoName = $_FILES['file']['name'];

            $searchValueParts = explode('.', $photoName);
            $name = $searchValueParts[0];
            $ext = $searchValueParts[1];

            $photoName = $prisonerId . "." . $ext; 

            $targetDirectory = 'uploads/';
            $targetFile = $targetDirectory . basename($photoName);
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        
                $query = "UPDATE photos SET `image`= '$photoName' WHERE `prisoner_id`='$prisonerId'";
                $result = mysqli_query($dbconn, $query);  
            }
            else //echo "Błąd podczas przesyłania pliku."; 
        }
    }
    else //echo "Nieprawidłowy sposób dostępu.";
}

?>

