<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	
	$user = $_SESSION["HeliUser"];

	$sql = "SELECT p.*, l.name, l.admin AS username FROM pilot_info p INNER JOIN login l ON l.id=p.id WHERE p.id=$user";
	$result = $mysqli->query($sql);
	if($result != FALSE){
		$row = mysqli_fetch_assoc($result);
		print_r(json_encode($row));
	}else{
		print_r("false");
	}

	mysqli_close($mysqli);
?>