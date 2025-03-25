<?php
	include_once "db_connect.php";
	include_once "validity.php";

	$account = $_SESSION["account"];
	if(isset($_GET["type"])){
		$type = $_GET["type"];
	}else{
		$type = "pil";
	}

	$maxShifts = $mysqli->query("SELECT max_days_in_row FROM account_info WHERE id=$account")->fetch_assoc()["max_days_in_row"];
	if(isset($_GET["date"])){
		$cur = date("Y-m-d", strtotime($_GET["date"]." 12:00:00"));
		$past = date("Y-m-d", strtotime($cur." - $maxShifts days"));
	}else{
		$cur = date("Y-m-d");
		$past = date("Y-m-d", strtotime($cur." - $maxShifts days"));
	}
	
	//query for id's that have been scheduled 6 days in a row
	$schedAr = array();
	for($c = 0; $c < $maxShifts; $c++){
		$query = "SELECT `id` FROM schedule WHERE `sched_date`='$past' AND `id` IN (SELECT id FROM pilot_info WHERE account=$account)";
		$result = $mysqli->query($query);
		if($result != false){
			while($row = mysqli_fetch_assoc($result)){
				isset($schedAr[$row["id"]]) ? $schedAr[$row["id"]] += 1 : $schedAr[$row["id"]] = 1;
			}
		}
		$past = date("Y-m-d", strtotime($past." + 1 day"));
	}

	$craft = $_GET["craft"];
	$query = "SELECT craft FROM crafts WHERE id=$craft";
	$result = $mysqli->query($query);
	$craftType = $result->fetch_assoc()["craft"];
	$craftCondition = "AND p.crafts LIKE '%$craftType%'";

	$valHelper = new Validity();
	if($_GET["strict"] == "2" || $_GET["strict"] == '1'){
		$strictVal = intval($_GET['strict']);
		$validPilotIds = $valHelper->getValidPilotIds($strict, $cur, true);

		$required = "AND p.id IN $validPilotIds";
	}else{
		$required = "";
		$strict = "";
	}

	
	if($_GET["contract"] != "any"){
		$contr = $_GET["contract"];
		$contract = "AND contracts LIKE '%$contr%'";
	}else{
		$contract = "";
	}

	$backOne = date("Y-m-d", strtotime($cur." - 1 day"));
	$end = date("Y-m-d", strtotime($cur." + 1 day"));
	$tod = $_GET["tod"];
	if($tod == "day"){
		$todCheck = "AND p.id NOT IN (SELECT s.id FROM schedule s INNER JOIN crafts c ON c.id=s.craft WHERE s.sched_date='$backOne' AND c.tod!='$tod') ";
	}else{
		$todCheck = "";
	}
	//query for valid, non-scheduled, in-the-same-class pilots
	if($type == "com"){
		$sql = "SELECT DISTINCT p.id, p.fname, p.lname 
		FROM pilot_info p INNER JOIN login l ON l.id=p.id 
		WHERE p.comandante=1 AND p.id IN (SELECT DISTINCT id FROM available WHERE `on`<'$cur' AND `off`>'$cur') 
		$strict $required $contract $craftCondition AND p.id NOT IN (SELECT id FROM schedule WHERE `sched_date`='$cur') 
		AND p.id NOT IN (SELECT pilot_id1 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id1 IS NOT NULL) 
		AND p.id NOT IN (SELECT pilot_id2 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id2 IS NOT NULL) 
		AND p.id NOT IN (SELECT pilot_id3 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id3 IS NOT NULL) 
		AND p.id NOT IN (SELECT pilot_id4 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id4 IS NOT NULL) 
		AND p.id NOT IN (SELECT tri1_id FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND tri1_id IS NOT NULL) 
		AND p.id NOT IN (SELECT tri2_id FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND tri2_id IS NOT NULL) 
		AND p.id NOT IN (SELECT tre_id FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND tre_id IS NOT NULL) 
		$todCheck
		AND p.account=$account AND l.admin IN (0,1,2,3,8)";
	}elseif($type == "pil"){
		$sql = "SELECT p.id, p.fname, p.lname 
		FROM pilot_info p INNER JOIN login l ON l.id=p.id 
		WHERE p.id IN (SELECT DISTINCT id FROM available WHERE `on`<'$cur' AND `off`>'$cur') 
		$strict $required $contract $craftCondition AND p.id NOT IN (SELECT id FROM schedule WHERE sched_date='$cur') 
		AND p.id NOT IN (SELECT pilot_id1 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id1 IS NOT NULL) 
		AND p.id NOT IN (SELECT pilot_id2 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id2 IS NOT NULL) 
		AND p.id NOT IN (SELECT pilot_id3 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id3 IS NOT NULL) 
		AND p.id NOT IN (SELECT pilot_id4 FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND pilot_id4 IS NOT NULL) 
		AND p.id NOT IN (SELECT tri1_id FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND tri1_id IS NOT NULL) 
		AND p.id NOT IN (SELECT tri2_id FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND tri2_id IS NOT NULL) 
		AND p.id NOT IN (SELECT tre_id FROM training_schedule WHERE `date`<='$backOne' AND DATE_ADD(`date`, INTERVAL length DAY)>='$cur' AND tre_id IS NOT NULL) 
		$todCheck
		AND p.account=$account AND l.admin IN (0,1,2,3,8)";
	}
	$result = $mysqli->query($sql);
	error_log($sql."\n\r", 3, "dashlog.txt");
	if($result != FALSE){
		$res = array();
		$res[0]["text"] = ($type=="com" ? "Comandante" : "Piloto");
		$res[0]["value"] = 0;
		$i = 1;
		while($row = mysqli_fetch_assoc($result)){
			$inArray = false;
			if(isset($schedAr[$row["id"]]) && $schedAr[$row["id"]] >= $maxShifts){
				$inArray = true;
			}
			if(!$inArray){
				$res[$i]["text"] = $row["lname"].". ".strtoupper(substr($row["fname"], 0, 1));
				// $res[$i]["text"] = $row["lname"];
				$res[$i]["value"] = $row["id"];
				$i++;
			}	
		}

		print_r(json_encode($res));
	}else{
		print_r(json_encode(array("text"=>"No result..", "value"=>null)));
	}
		
	mysqli_close($mysqli);
?>