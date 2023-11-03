<?php

if(isset($_POST["query"]))
{	
    $dbHost = "mysql.agh.edu.pl:3306";
    $dbUsername = "anetabru"; 
    $dbPassword = "Aneta30112001";
    $dbName     = "anetabru";

    $connect = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
	
    $data = array();

	$condition = preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST["query"]);
	
 

	$query = "
	SELECT  p.name, p.surname, ch.prisoner_id, ch.cell_nr
    FROM cell_history AS ch
    JOIN prisoners AS p ON ch.prisoner_id = p.prisoner_id
	WHERE (p.surname LIKE '%" . $condition . "%' OR p.name LIKE '%" . $condition . "%' OR ch.prisoner_id LIKE '%" . $condition . "%') AND ch.to_date IS NULL
	AND p.in_prison = 1 
	ORDER BY ch.prisoner_id ASC
	LIMIT 10
";

	$result = $connect->query($query);

	//$replace_string = '<b>'.$condition.'</b>';

	foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	$row["surname"],
			'name'		=>	$row["name"],
			'prisoner_id'		=>	$row["prisoner_id"],
			'cellNumber' => $row["cell_nr"],
		);
	}

	/*foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	str_ireplace($condition, $replace_string, $row["surname"]),
			'name'		=>	str_ireplace($condition, $replace_string, $row["name"]),
			'prisoner_id'		=>	str_ireplace($condition, $replace_string, $row["prisoner_id"])
		);
	}*/

	header('Content-Type: application/json');
	echo json_encode($data);
}
?>