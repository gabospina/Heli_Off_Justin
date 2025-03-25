<?php
	include_once "db_connect.php";
	$username = strtolower($_GET["username"]);
	$query = "SELECT name FROM login WHERE name='$username'";
	$result = $mysqli->query($query);
	if($result->num_rows > 0){
		print("taken");
	}elseif($result->num_rows == 0){
		print("not taken");
	}else{
		print("error: ".$mysqli->error);
	}

?>