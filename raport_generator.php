<?php

    include 'logowanie.php'; 
    require_once './FPDF/fpdf.php'; //biblioteka

    $pdf = new FPDF();
    $pdf->AddPage();
    //$pdf->AddFont('RobotoRegular', '', './fonts/Roboto-Regular.ttf');
    //$pdf->AddFont('Arial', '');
    //$pdf->SetFont('RobotoRegular', '', 12); // czcionka, niepogrubiona, rozmiar 12
    $pdf->SetXY(10, 10);

    $host = "localhost";
    $port = 5432;
    $dbname = "Administration";
    $user = "anetabruzda";
    //$dbpassword1 = getenv("DB_PASSWORD");
    $dbpassword2 = 'Aneta30112001'; 

    //postgre
    //$dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$dbpassword2"); //działa
    //$query = "SELECT * FROM public.\"Prisoners\";";
    //$result_from_database = pg_query($dbconn, $query);

    //phpmyadmin
   
    $dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
    $query = "SELECT * FROM prisoners";
    $result_from_database = mysqli_query($dbconn, $query);

    $pdf->SetFont('Arial', 'B', 14); // czcionka, niepogrubiona, rozmiar 12

    $pdf->Cell(0,13, "Raport statystyczny", 0,1,'C');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12); // czcionka, niepogrubiona, rozmiar 12

    $pdf->Cell(0,7, "Malopolski Zaklad Karny", 0,1,'R');
    $pdf->Cell(0,7, "ul. Stroma 15", 0,1,'R');
    $pdf->Cell(0,7, "30-654 Poznan", 0,1,'R');
    $pdf->Cell(0,7, "Polska", 0,1,'R');
    $pdf->Ln();

    $czas_teraz = new DateTime();
    $_SESSION['czas'] = $czas_teraz;
    $format_czasu = 'Y-m-d'; 
    $sformatowany_czas = $czas_teraz->format($format_czasu);
    
    $query_count_all = "SELECT COUNT(*) as total FROM prisoners"; //total - alias
    $result_count_all = mysqli_query($dbconn, $query_count_all);
    $row_count_all = mysqli_fetch_array($result_count_all);
    $all_prisoners = $row_count_all['total'];

    $query_count_m = "SELECT COUNT(*) as m FROM prisoners where sex='M'";
    $result_count_m = mysqli_query($dbconn, $query_count_m);
    $row_count_m = mysqli_fetch_array($result_count_m);
    $all_m = $row_count_m['m'];

    $query_count_f = "SELECT COUNT(*) as f FROM prisoners where sex='F'"; 
    $result_count_f = mysqli_query($dbconn, $query_count_f);
    $row_count_f = mysqli_fetch_array($result_count_f);
    $all_f = $row_count_f['f'];


    $pdf->Cell(0,7, "Raport na dzien:  " . $sformatowany_czas, 0,1,'L');
    $pdf->Cell(0,7, "Wystawiony przez:   " . $_SESSION['name'] . " " . $_SESSION['surname'] , 0,1,'L');
    $pdf->Ln();

    $pdf->Cell(0,7, "Liczba wszytskich osadzonych:  " .$all_prisoners, 0,1,'L');
    $pdf->Cell(0,7, "Liczba mezczyzn:  " .$all_m, 0,1,'L');
    $pdf->Cell(0,7, "Liczba kobiet:  " .$all_f, 0,1,'L');
    $pdf->Ln();
    $pdf->Ln();


    while ($danex = mysqli_fetch_array($result_from_database)) {
        
        $pdf->Cell(40, 7, 'Imie: ' . $danex['name']);
        //$pdf->Ln(); //nowa linia
        $pdf->Cell(40, 7, 'Nazwisko: ' . $danex['surname']);
        $pdf->Ln(); //nowa linia
    }

    //generowanie pdf
    $pdf->Output('raport.pdf', 'I'); //d-download, i-inline (odczyt)

?>