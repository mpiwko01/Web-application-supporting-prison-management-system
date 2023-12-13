<?php
//Autosugestia pokazująca więźniów będących aktualnie w celi
//uzywana do przenosin wiezniow

if(isset($_POST["query"]))
{	
    $dbHost = "mysql.agh.edu.pl:3306";
    $dbUsername = "anetabru"; 
    $dbPassword = "Aneta30112001";
    $dbName     = "anetabru";

    $connect = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
	
    $data = array();

	$condition = preg_replace('/[^A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż0-9\- ]/', '', $_POST["query"]);
	$space = " ";

	$condition1 = $condition;
	$condition2 = "";
	$query="";

	if (strpos($condition, $space) !== false){
		$searchBar = explode(' ', $condition);
    	$condition1 = $searchBar[0]; 
    	$condition2 = $searchBar[1]; 
	}

	$query = "
	SELECT  p.name, p.surname, ch.prisoner_id, ch.cell_nr
    FROM cell_history AS ch
    JOIN prisoners AS p ON ch.prisoner_id = p.prisoner_id
	WHERE ((surname LIKE '".$condition1."' AND name LIKE '".$condition2."%') OR (surname LIKE '".$condition2."%' AND name LIKE '".$condition1."') OR (surname LIKE '".$condition."%' OR name LIKE '".$condition."%'))
	AND ch.to_date IS NULL
	AND p.in_prison = 1 
	ORDER BY ch.prisoner_id ASC
	LIMIT 10";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	$row["surname"],
			'name'		=>	$row["name"],
			'prisoner_id'		=>	$row["prisoner_id"],
			'cellNumber' => $row["cell_nr"],
		);
	}

	header('Content-Type: application/json');
	echo json_encode($data);
}
?>