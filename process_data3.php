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

	$query = "SELECT name, surname, prisoner_id FROM prisoners 
	WHERE (surname LIKE '%".$condition."%' OR name LIKE '%".$condition."%' OR prisoner_id LIKE '%".$condition."%')
	ORDER BY prisoner_id ASC 
	LIMIT 10";

	$result = $connect->query($query);

	//$replace_string = '<b>'.$condition.'</b>';

	foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	$row["surname"],
			'name'		=>	$row["name"],
			'prisoner_id'		=>	$row["prisoner_id"]
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

	echo json_encode($data);
}
?>