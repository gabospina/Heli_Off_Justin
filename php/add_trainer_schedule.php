<?php
	include_once "db_connect.php";
	$date = $_POST["start"];
	$length = $_POST["length"];
	$pilot = $_POST["pilot"];
	$position = $_POST["position"];

	$insert = "INSERT INTO trainer_schedule VALUES(null, '$pilot', '$date', $length, '$position')";
	if($mysqli->query($insert)){
		print("success");
	}else{
		print("failed: ".$mysqli->error);
	}
?>