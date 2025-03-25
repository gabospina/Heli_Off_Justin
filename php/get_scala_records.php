<?php
	include_once "db_connect.php";
	$date = $_GET["date"];
	$account = $_SESSION["account"];
	$maxFlightInDay = floatval($mysqli->query("SELECT max_in_day FROM account_info WHERE id=$account")->fetch_assoc()["max_in_day"]);
	$maxFlight7 = floatval($mysqli->query("SELECT max_last_7 FROM account_info WHERE id=$account")->fetch_assoc()["max_last_7"]);
	$maxFlight28 = floatval($mysqli->query("SELECT max_last_28 FROM account_info WHERE id=$account")->fetch_assoc()["max_last_28"]);
	$maxFlight365 = floatval($mysqli->query("SELECT max_last_365 FROM account_info WHERE id=$account")->fetch_assoc()["max_last_365"]);
	
	$maxDutyInDay = floatval($mysqli->query("SELECT max_duty_in_day FROM account_info WHERE id=$account")->fetch_assoc()["max_duty_in_day"]);
	$maxDuty7 = floatval($mysqli->query("SELECT max_duty_7 FROM account_info WHERE id=$account")->fetch_assoc()["max_duty_7"]);
	$maxDuty28 = floatval($mysqli->query("SELECT max_duty_28 FROM account_info WHERE id=$account")->fetch_assoc()["max_duty_28"]);
	$maxDuty365 = floatval($mysqli->query("SELECT max_duty_365 FROM account_info WHERE id=$account")->fetch_assoc()["max_duty_365"]);

	$back7 = date("Y-m-d", strtotime($date." - 7 days"));
	$back28 = date("Y-m-d", strtotime($date." - 28 days"));
	$back365 = date("Y-m-d", strtotime($date." - 365 days"));

	$query = "SELECT s.*, CONCAT(p.lname,', ', p.fname) AS pilot_name FROM scala_records s INNER JOIN pilot_info p ON p.id=s.pilot_id WHERE `date`='$date' AND p.account=$account ORDER BY s.craft, s.position";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			$minDaily = round(($row['daily']-intval($row['daily']))*60);
			$row['daily'] = intval($row['daily']).":".($minDaily > 9 ? $minDaily : '0'.$minDaily);
			$minFlown = round(($row['flown']-intval($row['flown']))*60);
			$row['flown'] = intval($row['flown']).":".($minFlown > 9 ? $minFlown : '0'.$minFlown);

			if($row["daily"] > $maxDutyInDay){
				$row["overDaily"] = true;
			}else{
				$row["overDaily"] = false;
			}
			if($row["flown"] > $maxFlightInDay){
				$row["overFlight"] = true;
			}else{
				$row["overFlight"] = false;
			}

			//check if over the past 7 days for flight
			if(floatval($mysqli->query("SELECT SUM(flown) AS total FROM scala_records WHERE pilot_id=$row[pilot_id] AND `date` BETWEEN '$back7' AND '$date'")->fetch_assoc()["total"]) > $maxFlight7){
				$row["over7Flight"] = true;
			}else{
				$row["over7Flight"] = false;
			}
			//check ifover the past 7 days for daily
			if(floatval($mysqli->query("SELECT SUM(daily) AS total FROM scala_records WHERE pilot_id=$row[pilot_id] AND `date` BETWEEN '$back7' AND '$date'")->fetch_assoc()["total"]) > $maxDuty7){
				$row["over7Daily"] = true;
			}else{
				$row["over7Daily"] = false;
			}
			//check if over the past 28 days for flight
			if(floatval($mysqli->query("SELECT SUM(flown) AS total FROM scala_records WHERE pilot_id=$row[pilot_id] AND `date` BETWEEN '$back28' AND '$date'")->fetch_assoc()["total"]) > $maxFlight28){
				$row["over28Flight"] = true;
			}else{
				$row["over28Flight"] = false;
			}
			//check ifover the past 28 days for daily
			if(floatval($mysqli->query("SELECT SUM(daily) AS total FROM scala_records WHERE pilot_id=$row[pilot_id] AND `date` BETWEEN '$back28' AND '$date'")->fetch_assoc()["total"]) > $maxDuty28){
				$row["over28Daily"] = true;
			}else{
				$row["over28Daily"] = false;
			}
			//check if over the past 365 days for flight
			if(floatval($mysqli->query("SELECT SUM(flown) AS total FROM scala_records WHERE pilot_id=$row[pilot_id] AND `date` BETWEEN '$back365' AND '$date'")->fetch_assoc()["total"]) > $maxFlight365){
				$row["over365Flight"] = true;
			}else{
				$row["over365Flight"] = false;
			}
			//check ifover the past 28 days for daily
			if(floatval($mysqli->query("SELECT SUM(daily) AS total FROM scala_records WHERE pilot_id=$row[pilot_id] AND `date` BETWEEN '$back365' AND '$date'")->fetch_assoc()["total"]) > $maxDuty365){
				$row["over365Daily"] = true;
			}else{
				$row["over365Daily"] = false;
			}

			array_push($res, $row);
		}
	}else{
		$res["error"] = $mysqli->error;
		$res["query"] = $query;
	}
	print(json_encode($res));
?>