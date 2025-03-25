<?php
	include_once "db_connect.php";

	$pilots = json_decode($_POST["pilots"], true);
	$craft = $_POST["craft"];

	$pilotSuccess = true;
	for($i = 0; $i < count($pilots); $i++){
		$pilot = $pilots[$i];
		$sql = "UPDATE pilot_info SET crafts = CONCAT(crafts, '$craft;') WHERE id=$pilot";
		$update = $mysqli->query($sql);
		if(!$update){
			$pilotSuccess = false;
		}
	}
	if($pilotSuccess){
		print("success");
	}else{
		print("failed");
	}
?>