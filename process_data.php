<?php

//process_data.php

//$connect = new PDO("mysql:host=localhost;dbname=", "root", "");
//$dbHost     = "localhost";  //  your hostname
//$dbUsername = "sgarnca1";       //  your table username
//$dbPassword = "7mpcZLL4tQdA94P9";          // your table password
//$dbName     = "sgarnca1";  // your database name
 
// Create database connection 
//$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
//$connect = new PDO("mysql:host=localhost;dbname=sgarnca1", "sgarnca1", "7mpcZLL4tQdA94P9");

if(isset($_POST["query"]))
{	
    //$dbHost     = "localhost";  //  your hostname
	//$dbconn = mysqli_connect("mysql.agh.edu.pl:3306", "anetabru", "Aneta30112001", "anetabru");
    $dbHost = "mysql.agh.edu.pl:3306";
    $dbUsername = "anetabru";       //  your table username
    $dbPassword = "Aneta30112001";          // your table password
    $dbName     = "anetabru";  // your database name
 
    // Create database connection 
    $connect = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
    //$connect = new PDO("mysql:host=localhost;dbname=sgarnca1", "sgarnca1", "7mpcZLL4tQdA94P9");
    //$connect = new PDO("mysql:host=localhost;dbname=sgarnca1", "sgarnca1", "7mpcZLL4tQdA94P9");
	
    $data = array();

	$condition = preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST["query"]);

	$query = "
	SELECT surname FROM prisoners 
		WHERE surname LIKE '%".$condition."%' 
		ORDER BY nr DESC 
		LIMIT 10
	";

	$result = $connect->query($query);

	$replace_string = '<b>'.$condition.'</b>';

	foreach($result as $row)
	{
		$data[] = array(
			'surname'		=>	str_ireplace($condition, $replace_string, $row["surname"])
		);
	}

	echo json_encode($data);
}
?>