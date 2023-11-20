<?php

// Sprawdzenie, czy żądanie jest metodą POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        // Przypisanie danych z żądania do zmiennych
        $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
        // Sprawdzenie połączenia
        if ($dbconn->connect_error) {
            die("Connection failed: " . $dbconn->connect_error);
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $event_id = $data['event_id'];

        // Zapytanie SQL do usunięcia wiersza z bazy danych na podstawie przekazanych danych wydarzenia
        $sql = "DELETE FROM passes WHERE pass_id = '$event_id'";

        // Wykonanie zapytania i sprawdzenie powodzenia
        if ($dbconn->query($sql) === TRUE) {
            echo json_encode(["status" => true, "msg" => "Wydarzenie zostało pomyślnie usunięte."]);
        } else {
            echo json_encode(["status" => false, "msg" => "Błąd podczas usuwania wydarzenia: " . $dbconn->error]);
        }
} else {
    // Metoda żądania nie jest POST
    echo json_encode(["status" => false, "msg" => "Nieprawidłowa metoda żądania."]);
}

// Zamknięcie połączenia
$dbconn->close();
?>