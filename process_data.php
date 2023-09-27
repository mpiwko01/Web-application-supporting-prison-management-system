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
	SELECT name, surname, nr FROM prisoners 
		WHERE surname LIKE '%".$condition."%' OR name LIKE '%".$condition."%' OR nr LIKE '%".$condition."%'
		ORDER BY nr ASC 
		LIMIT 10
	";

	$result = $connect->query($query);

	$replace_string = '<b>'.$condition.'</b>';

	foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	str_ireplace($condition, $replace_string, $row["surname"]),
			'name'		=>	str_ireplace($condition, $replace_string, $row["name"]),
			'nr'		=>	str_ireplace($condition, $replace_string, $row["nr"])
		);
	}

	echo json_encode($data);
}
?>