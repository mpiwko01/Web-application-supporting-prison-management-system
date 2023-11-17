<?php

function prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate) {

    $cell_counter_query = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `cell_nr` = '$selectedCell' AND `to_date` IS NULL"; //sprawdza czy w wybranej celi sa osadzeni

    $result_cell_counter = mysqli_query($dbconn, $cell_counter_query);

    if($result_cell_counter) {
        $row_cell_counter = mysqli_fetch_assoc($result_cell_counter);
        $count = $row_cell_counter['query_counter'];
        $prisoner_count = 0;
        if ($count == 0) $prisoner_count = 0;
        else if ($count > 0 && $count < 4) $prisoner_count = 1;
        else if ($count >= 4) $prisoner_count = 2;
        return $prisoner_count;
    }
};

function FloorCheck($dbconn, $prisoner_id,$selectedCell) {
    $prisoner_sex = sexOfPrisoner($dbconn, $prisoner_id);

    if (($selectedCell >= 1 && $selectedCell <= 6) && $prisoner_sex == 'F') {
        return true;
    } else if (($selectedCell > 6 && $selectedCell <= 12) && $prisoner_sex == 'M') {
        return true;
    } 
    return false;
}

function sexOfPrisoner($dbconn, $prisoner_id) {

    $prisoner_sex_query = "SELECT sex FROM prisoners WHERE `prisoner_id`='$prisoner_id'"; //plec dodawanego wieznia

    $result_prisoner_sex = mysqli_query($dbconn, $prisoner_sex_query);

    if($result_prisoner_sex) {
        $row_prisoner_sex = mysqli_fetch_assoc($result_prisoner_sex);
        $prisoner_sex = $row_prisoner_sex['sex'];
        return $prisoner_sex;
    }
}

function prisonerSex($dbconn, $prisoner_id, $selectedCell, $selectedDate) {

    $prisoner_sex = sexOfPrisoner($dbconn, $prisoner_id);

    $prisoner_count = prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate); //sprawdzamy czy cela jest pusta bo wtedy nie trzeba sprawdzac czy plec wieznia pasuje
    if($prisoner_count == 0) { //cela jest pusta
        $prisoner_sex = 0;
        return $prisoner_sex;
    }
    else { //ktos jest w celi - sprawdzamy plec osadzonych
        $cell_sex = "SELECT sex FROM prisoners WHERE `prisoner_id` IN (SELECT prisoner_id FROM cell_history WHERE `cell_nr` = '$selectedCell' AND `to_date` IS NULL) LIMIT 1"; //plec osadzonych

        $result_cell_sex = mysqli_query($dbconn, $cell_sex);

        if($result_cell_sex) {
            $row_cell_sex = mysqli_fetch_assoc($result_cell_sex);
            $cell_sex = $row_cell_sex['sex'];

            if ($cell_sex == $prisoner_sex) { //plec wieznia zgadza sie z plcią osadzonych - mozna dodac
                $prisoner_sex = 0;
                return $prisoner_sex;
            }
            else { //plec sie nie zgadza
                if ($prisoner_sex == 'F') $prisoner_sex = 1;
                else if ($prisoner_sex == 'M') $prisoner_sex = 2;
                return $prisoner_sex;
            }
        }
    }
};

function ageOfPrisoner($dbconn, $prisoner_id, $selectedDate) {

    $prisoner_birth_date_query = "SELECT birth_date FROM prisoners WHERE `prisoner_id`='$prisoner_id'"; //data urodzenia dodawanego wieznia

    $result_prisoner_birth_date = mysqli_query($dbconn, $prisoner_birth_date_query);

    $date = new DateTime();
    $format = 'Y'; 
    $curDate = $date->format($format); //dzisiejsza data

    if ($result_prisoner_birth_date) {
        $row_prisoner_birth_date = mysqli_fetch_assoc($result_prisoner_birth_date);
        $prisoner_birth_date = $row_prisoner_birth_date['birth_date'];

        $prisoner_birth_date = new DateTime($prisoner_birth_date);
        $format = 'Y'; 
        $prisoner_birth_year = $prisoner_birth_date->format($format);

        $prisonerAge = $date->diff($prisoner_birth_date)->y; //wiek dodawanego wieznia
        return $prisonerAge;
    } 
}

function prisonerAge($dbconn, $prisoner_id, $selectedCell, $selectedDate) {

    $prisonerAge = ageOfPrisoner($dbconn, $prisoner_id, $selectedDate);

    $prisoner_count = prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate); //sprawdzamy czy ktos jest w wybranej celi

    if($prisoner_count == 0) { //cela jest pusta, nie trzeba sprawdzac wieku
        $prisoner_age = true;
        return $prisoner_age;
    }
    else { //ktos jest w celi - sprawdzamy  wiek
        $prisoners_age_query = "SELECT birth_date FROM prisoners AS p INNER JOIN cell_history as ch ON p.prisoner_id = ch.prisoner_id AND cell_nr = '$selectedCell'"; //pobiera daty urodzen wiezniow w celi

        $result_prisoners_age_query = mysqli_query($dbconn, $prisoners_age_query);

        if ($result_prisoners_age_query) {
            while ($row = mysqli_fetch_assoc($result_prisoners_age_query)) {
                $prisoners_birth_date = $row['birth_date'];

                $prisoners_birth_date = new DateTime($prisoners_birth_date);
                $format = 'Y'; 
                $prisoners_birth_year = $prisoners_birth_date->format($format);

                $date = new DateTime();
                $prisoners_age = $date->diff($prisoners_birth_date)->y;

                if(abs($prisonerAge - $prisoners_age) > 15) $prisoner_age = false;
                else $prisoner_age = true;
                return $prisoner_age;
            }
        }
    }   
};

function prisonerInCell($dbconn, $prisoner_id, $selectedCell) {

    $cell_counter_query = "SELECT COUNT(*) as query_counter FROM cell_history WHERE `prisoner_id` = '$prisoner_id' AND `to_date` IS NULL";

    $result_cell_counter_query = mysqli_query($dbconn, $cell_counter_query);

    if($result_cell_counter_query) {
        $row_cell_counter_query = mysqli_fetch_assoc($result_cell_counter_query);
        $count = $row_cell_counter_query['query_counter']; //czy wiezien jest w jakiejs celi przypisany

        if ($count != 0) return true;
        else return false; //nieprzypisany do zadnej
    }
};

function presentCell($dbconn, $prisoner_id, $selectedCell) {

    if (prisonerInCell($dbconn, $prisoner_id, $selectedCell)) { //jesli przypisany
        $cell_query = "SELECT cell_nr FROM cell_history WHERE `prisoner_id` = '$prisoner_id' AND `to_date` IS NULL";
        $result_cell_query = mysqli_query($dbconn, $cell_query);
        
        if ($result_cell_query) {
            $row_cell_query = mysqli_fetch_assoc($result_cell_query);
            $presentCell = $row_cell_query['cell_nr'];
            if ($presentCell != $selectedCell) return true;
            else return false;
        }
    }
    else return true;   
};

function correctDate($dbconn, $prisoner_id, $selectedCell, $selectedDate) {
    $query_cell = "SELECT * FROM cell_history WHERE `prisoner_id`='$prisoner_id' AND `to_date` IS NULL";
    $result_cell = mysqli_query($dbconn, $query_cell); //pobieram gdzie obecnie znajduje sie wiezien

    if ($result_cell) {
        $row = mysqli_fetch_array($result_cell);
        $currentCell =  $row['cell_nr'];
        $currentDate = $row['from_date'];

        if(strtotime($currentDate) < strtotime($selectedDate)) return true;
        else return false;
    }
};

function severityPrisoner($dbconn, $prisoner_id) {

    $query = "SELECT crimes.severity FROM prisoners INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id WHERE prisoners.prisoner_id = '$prisoner_id' AND prisoner_sentence.release_date IS NULL";

    $result = mysqli_query($dbconn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $prisoner_severity = $row['severity'];
        return $prisoner_severity;
    }
};

function crimeSeverity($dbconn, $prisoner_id, $selectedCell, $selectedDate) {

    if(prisonerInCell($dbconn, $prisoner_id, $selectedCell)) {
        $query = "SELECT crimes.severity FROM prisoners INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id INNER JOIN cell_history ON cell_history.prisoner_id = prisoners.prisoner_id WHERE prisoners.prisoner_id = '$prisoner_id' AND prisoner_sentence.release_date IS NULL AND cell_history.to_date IS NULL"; 
    }
    else {
        $query = "SELECT crimes.severity FROM prisoners INNER JOIN prisoner_sentence ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN crimes ON prisoner_sentence.crime_id = crimes.crime_id WHERE prisoners.prisoner_id = '$prisoner_id' AND prisoner_sentence.release_date IS NULL";
    }

    $result = mysqli_query($dbconn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $prisoner_severity = $row['severity'];

        $prisoner_count = prisonerCount($dbconn, $prisoner_id, $selectedCell, $selectedDate); //sprawdzamy czy ktos jest w wybranej celi

        if($prisoner_count == 0) { //cela jest pusta, nie trzeba sprawdzac ciezkossci przestepstwa
            $prisoner_severity = true;
            return $prisoner_severity;
        }
        else {
            $prisoners_severity_query = "SELECT severity FROM crimes INNER JOIN prisoner_sentence ON prisoner_sentence.crime_id = crimes.crime_id INNER JOIN prisoners ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN cell_history ON cell_history.prisoner_id = prisoners.prisoner_id AND cell_history.cell_nr = '$selectedCell' AND cell_history.to_date iS NULL AND prisoner_sentence.release_date IS NULL"; //pobiera ciezkosc przestepstw wiezniow w celi

            $result_severity_query = mysqli_query($dbconn, $prisoners_severity_query);

            if ($result_severity_query) {
                while ($row = mysqli_fetch_assoc($result_severity_query)) {
                    $prisoners_severity = $row['severity'];

                    if($prisoner_severity != $prisoners_severity) $prisoner_severity = false;
                    else $prisoner_severity = true;
                    return $prisoner_severity;
                }
            }
        }
    }
};

function suggestCell($dbconn) {

    $query = "SELECT cell_nr, COUNT(*) AS count
    FROM cell_history WHERE `to_date` IS NULL GROUP BY cell_nr"; //zwraca liczbe rekordow dla kazdego numeru celi

    $result = mysqli_query($dbconn, $query);

    $available_cell = [1,2,3,4,5,6,7,8,9,10,11,12];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cell_count = $row['count'];
            $cell_nr = $row['cell_nr'];
            if ($cell_count >= 4) {
                $index = array_search($cell_nr, $available_cell);
                if ($index !== false) unset($available_cell[$index]);
            }
        }
        return $available_cell;
    }
};

function suggestSex($dbconn, $prisoner_id) {

    $prisoner_sex = sexOfPrisoner($dbconn, $prisoner_id);

    $cell_sex = "SELECT DISTINCT cell_history.cell_nr, prisoners.sex FROM prisoners INNER JOIN cell_history ON prisoners.prisoner_id = cell_history.prisoner_id WHERE cell_history.to_date IS NULL;"; //plec osadzonych w kazdej obecnie zajetej celi

    $result = mysqli_query($dbconn, $cell_sex);

    if ($prisoner_sex == 'F') {
        $available_cell = [1,2,3,4,5,6];
    }
    else if ($prisoner_sex == 'M') {
        $available_cell = [7,8,9,10,11,12];
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sex = $row['sex'];
            $cell_nr = $row['cell_nr'];
            
            if ($prisoner_sex != $sex) {
                $index = array_search($cell_nr, $available_cell);
                if ($index !== false) unset($available_cell[$index]);
            }
        }
        return $available_cell;
    }
};

function suggestAge($dbconn, $prisoner_id, $selectedDate) {

    $prisonerAge = ageOfPrisoner($dbconn, $prisoner_id, $selectedDate);

    $prisoners_age_query = "SELECT birth_date, cell_nr FROM prisoners AS p INNER JOIN cell_history as ch ON p.prisoner_id = ch.prisoner_id AND ch.to_date IS NULL"; //pobiera daty urodzen wiezniow obecnie osadzonych w celach

    $result_prisoners_age_query = mysqli_query($dbconn, $prisoners_age_query);

    $available_cell = [1,2,3,4,5,6,7,8,9,10,11,12];

    if ($result_prisoners_age_query) {
        while ($row = mysqli_fetch_assoc($result_prisoners_age_query)) {
            $prisoners_birth_date = $row['birth_date'];
            $cell_nr = $row['cell_nr'];

            $prisoners_birth_date = new DateTime($prisoners_birth_date);
            $format = 'Y'; 
            $prisoners_birth_year = $prisoners_birth_date->format($format);

            $date = new DateTime();
            $prisoners_age = $date->diff($prisoners_birth_date)->y;

            if(abs($prisonerAge - $prisoners_age) > 15) {
                if (in_array($cell_nr, $available_cell)) {
                    $index = array_search($cell_nr, $available_cell);
                    unset($available_cell[$index]);
                }
            }
        }
        return $available_cell;
    }
};

function suggestSeverity($dbconn, $prisoner_id) {

    $severity = severityPrisoner($dbconn, $prisoner_id);

    $prisoners_severity_query = "SELECT severity, cell_nr FROM crimes INNER JOIN prisoner_sentence ON prisoner_sentence.crime_id = crimes.crime_id INNER JOIN prisoners ON prisoners.prisoner_id = prisoner_sentence.prisoner_id INNER JOIN cell_history ON cell_history.prisoner_id = prisoners.prisoner_id AND cell_history.to_date iS NULL AND prisoner_sentence.release_date IS NULL"; //pobiera ciezkosc przestepstw obecnych wiezniow w celach

    $result = mysqli_query($dbconn, $prisoners_severity_query);

    $available_cell = [1,2,3,4,5,6,7,8,9,10,11,12];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $prisoners_severity = $row['severity'];
            $cell_nr = $row['cell_nr'];

            if($prisoners_severity != $severity) {
                if (in_array($cell_nr, $available_cell)) {
                    $index = array_search($cell_nr, $available_cell);
                    unset($available_cell[$index]);
                }
            }
        }
        return $available_cell;
    }
};

function suggestion($dbconn, $prisoner_id, $selectedDate) {

    $suggestedCell = suggestCell($dbconn);
    $suggestedSex = suggestSex($dbconn, $prisoner_id);
    $suggestedAge = suggestAge($dbconn, $prisoner_id, $selectedDate);
    $suggestedSeverity = suggestSeverity($dbconn, $prisoner_id);

    $result = array();

    $result = array_unique(array_intersect($suggestedCell, $suggestedSex, $suggestedAge, $suggestedSeverity));

    return $result;
};




?>