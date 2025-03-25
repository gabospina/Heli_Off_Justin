<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$name = $_POST["name"];
	$crafts = json_decode($_POST["crafts"]);
	$pilots = json_decode($_POST["pilots"]);
	$order = $_POST["order"];
	$color = $_POST["color"];
	$account = $_SESSION["account"];

	$insertSuccess = true;
	$sql = "INSERT INTO contract_info VALUES(null, '$name', $order, '$color', $account)";
	$result = $mysqli->query($sql);
	if($result != false){
		$id = $mysqli->insert_id;
		for($i = 0; $i < count($crafts); $i++){
			$craft = $crafts[$i];
			$sql = "INSERT INTO contracts VALUES($id, $craft)";
			$insert = $mysqli->query($sql);
			if(!$insert){
				$insertSuccess = false;
			}
		}
		if($insertSuccess){
			$pilotSuccess = true;
			for($i = 0; $i < count($pilots); $i++){
				$pilot = $pilots[$i];
				$sql = "UPDATE pilot_info SET contracts = CONCAT(contracts, '$name;') WHERE id=$pilot";
				$update = $mysqli->query($sql);
				if(!$update){
					$pilotSuccess = false;
				}
			}
			if($pilotSuccess){
				print("success");
			}else{
				print("failed update");
			}
		}else{
			print("failed insert");
		}
	}	
?>