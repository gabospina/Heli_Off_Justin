<?php
	include_once "db_connect.php";

	$start = $_GET["start"];
	$craft = $_GET["craft"];
	$end = $_GET["end"];
	$account = $_SESSION["account"];
	$backSeven = date("Y-m-d", strtotime($start." - 7 days"));

	$query = "SELECT DISTINCT p.id, p.lname, p.fname FROM pilot_info p  
	WHERE p.id NOT IN (SELECT tre_id FROM training_schedule WHERE `date`>='$backSeven' AND `date` <='$end' AND `craft` != '$craft')
	AND p.id NOT IN (SELECT tri1_id FROM training_schedule WHERE `date`>='$backSeven' AND `date` <='$end' AND `craft` != '$craft' AND tri1_id IS NOT NULL) 
	AND p.id NOT IN (SELECT tri2_id FROM training_schedule WHERE `date`>='$backSeven' AND `date` <='$end' AND `craft` != '$craft' AND tri2_id IS NOT NULL)
	AND p.crafts LIKE '%$craft%' AND (p.training=1 OR p.training=2) AND p.account=$account ORDER BY p.lname";
	// error_log("\nTrainer Query: $query", 3, "../logs/log.txt");
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			array_push($res, array("id"=>$row["id"], "name"=>$row["lname"].", ".$row["fname"]));
		}
	}
	print(json_encode($res));
?>