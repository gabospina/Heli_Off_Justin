<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	include_once "db_connect.php";
	$user = $_SESSION["HeliUser"];
	$query = "SELECT clock_name AS name, clock_tz AS tz FROM pilot_info WHERE id=$user";
	$result = $mysqli->query($query);
	print(json_encode($result->fetch_assoc()));
?>