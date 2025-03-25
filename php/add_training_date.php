<?php
	include_once "db_connect.php";
	$on = $_POST["start"];
	$off = $_POST["end"];
	$query = "INSERT INTO training_available VALUES(null, '$on', '$off')";
	$result = $mysqli->query($query);
	if($result){
		print("success=".$mysqli->insert_id);
	}else{
		print($mysqli->error);
	}

?>