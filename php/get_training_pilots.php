<?php
	include_once "db_connect.php";
	include_once 'validity.php';

	$start = $_GET["start"];
	$craft = $_GET["craft"];
	$end = $_GET["end"];
	$backSeven = date("Y-m-d", strtotime($start." - 7 days"));
	$backOne = date("Y-m-d", strtotime($start." - 1 day"));
	$threemonth = date("Y-m-d", strtotime($start." + 3 months"));
	$ninemonth = date("Y-m-d", strtotime($start." - 9 months"));
	$account = $_SESSION["account"];

	$valHelper = new Validity();
	$simId = $valHelper->getIdFromKey('sim');
	if(isset($_GET["current"])){
		$currentlySelected = $_GET["current"];
		$query = "SELECT DISTINCT p.id, p.lname, p.fname, e.value FROM pilot_info p INNER JOIN available a ON a.id=p.id INNER JOIN account_validity_values e ON e.pilot_id=p.id AND e.field_id=$simId
		WHERE p.id IN $currentlySelected
		OR (p.id NOT IN (SELECT pilot_id1 FROM training_schedule WHERE `date`>='$ninemonth') 
			AND p.id NOT IN (SELECT pilot_id2 FROM training_schedule WHERE `date`>='$ninemonth') 
			AND p.id NOT IN (SELECT id FROM schedule WHERE `sched_date`>='$backOne' AND `sched_date` <='$end') 
			AND p.id IN (SELECT pilot_id FROM account_validity_values WHERE field_id={$simId} AND (value IS NULL OR value <='$threemonth')) AND p.crafts LIKE '%$craft%') 
			AND p.account=$account ORDER BY p.lname";
	}else{
		// error_log("\nBACK ONE: $backOne", 3, "../logs/log.txt");
		$query = "SELECT DISTINCT p.id, p.lname, p.fname, e.value FROM pilot_info p INNER JOIN available a ON a.id=p.id INNER JOIN account_validity_values e ON e.pilot_id=p.id AND e.field_id=$simId
		WHERE p.id NOT IN (SELECT pilot_id1 FROM training_schedule WHERE `date`>='$ninemonth') 
		AND p.id NOT IN (SELECT pilot_id2 FROM training_schedule WHERE `date`>='$ninemonth') 
		AND p.id NOT IN (SELECT id FROM schedule WHERE `sched_date`>='$backOne' AND `sched_date` <='$end') 
		AND p.id IN (SELECT pilot_id FROM account_validity_values WHERE field_id={$simId} AND (`value` IS NULL OR `value` <='$threemonth')) AND p.crafts LIKE '%$craft%'
		AND p.account=$account ORDER BY p.lname";
	}
		
	// error_log("\nTraining Query: $query", 3, "../logs/log.txt");
	$result = $mysqli->query($query);
	// error_log("\nsql: $query", 3, "../logs/log.txt");
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			$id = $row["id"];
			$treQuery = "SELECT trainer_id FROM training_schedule WHERE pilot_id=$id AND `date`='$backOne'";
			$treResult = $mysqli->query($treQuery);
			$trainer = false;
			if($treResult != false && $treResult->num_rows == 1){
				$trainer = $treResult->fetch_assoc()["trainer_id"];
			}
			//query to determine if the pilot's sim has expired
			$expireType = "";
			if($row["sim"] != null){
				$sim = strtotime($row["sim"]);
				if($sim < time()){
					$expireType = "alert-danger";
				}else if($sim < (time()+(86400*90))){
					$expireType = "alert-warning";
				}
			}
			
			array_push($res, array("id"=>$row["id"], "name"=>$row["lname"].", ".$row["fname"], "trainer"=>$trainer, "expired"=>$expireType));
		}
	}else{
		print($mysqli->error);
	}
	print(json_encode($res));
?>