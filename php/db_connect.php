<?php	
	$db_hostname = "localhost";
	$db_name     = "heli_offshore";
	$db_username = "root";
	$db_password = "";
	// $db_username = "gabospina"; //database username
	// $db_password = "sf_$+THbO]uA"; //database pass
	$mysqli = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

	if($mysqli->connect_errno > 0)
	{
		die('Unable to connect to database [' . $mysqli->connect_error . ']');
	}
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
?>