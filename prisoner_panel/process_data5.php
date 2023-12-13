<?php
// Autosugestia pokazująca wiezniow ktorzy opuscili wiezienie
//uzywana w prisoner_archive.php w wyszukiwaniu (archiwum)
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

	$query = "SELECT name, surname, prisoner_id FROM prisoners 
		WHERE ((surname LIKE '".$condition1."' AND name LIKE '".$condition2."%') OR (surname LIKE '".$condition2."%' AND name LIKE '".$condition1."') OR (surname LIKE '".$condition."%' OR name LIKE '".$condition."%'))
		AND in_prison = 0 
		ORDER BY prisoner_id ASC 
		LIMIT 10";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	$row["surname"],
			'name'		=>	$row["name"],
			'prisoner_id'		=>	$row["prisoner_id"]
		);
	}

	echo json_encode($data);
}
?>