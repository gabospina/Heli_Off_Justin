<?php
	include_once "db_connect.php";

	$start = $_POST["start"];
	$end = $_POST["end"];
	$isSingleDay = intVal($_POST["isSingleDay"]);
	$account = $_SESSION["account"];

	$startTime = strtotime($start);
	$endTime = strtotime($end);
	//single day
	if($isSingleDay == 1){
		$query = "SELECT id FROM training_available WHERE `on`='$start' AND account=$account";
		$result = $mysqli->query($query);
		if($result != false && $result->num_rows != 0){
			$insert = "DELETE FROM training_available WHERE `on`='$start' AND account=$account";
			$mysqli->query($insert);
		}
	}else{
	//multiple days
		$days = ($endTime-$startTime)/86400;
		for($i = 0; $i <= $days; $i++){
			$day = date("Y-m-d", strtotime($start." + $i days"));
			$query = "SELECT id FROM training_available WHERE `on`='$day' AND account=$account";
			$result = $mysqli->query($query);
			if($result != false && $result->num_rows != 0){
				$insert = "DELETE FROM training_available WHERE `on`='$day' AND account=$account";
				$mysqli->query($insert);
			}
		}	
	}
?>