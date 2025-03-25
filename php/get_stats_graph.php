<?php
	include_once "db_connect.php";

	if (session_status() == PHP_SESSION_NONE) {
	      session_start();
	  }
	$user = $_SESSION["HeliUser"];
	$type = $_GET["type"];
	$start = $_GET["start"];

	$res = array();
	$total = 0;
	switch($type){
		case "week":
			$maxInDay = floatval($mysqli->query("SELECT max_in_day FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_in_day"]);
			$end = date("Y-m-d", strtotime($start." + 7 days"));
			$query = "SELECT * FROM hours WHERE pilot_id=$user AND date >= '$start' AND date < '$end' ORDER BY date";
			// error_log("\nQuery: $query", 3, "../logs/log.txt");
			$result = $mysqli->query($query);
			if($result != false){
				$dateEntries = array();
				while($row = $result->fetch_assoc()){
					if(!isset($dateEntries[$row["date"]]))
						$dateEntries[$row["date"]] = array("hours"=>$row["hours"], "label"=>$row["aircraft"]);
					else
						$dateEntries[$row["date"]]["hours"] += $row["hours"];
					$total += floatval($row["hours"]);
				}
				// error_log("\ndateEntries: ".json_encode($dateEntries), 3, "../logs/log.txt");
				for($i = 0; $i < 7; $i++){
					$date = date("Y-m-d", strtotime($start." + $i days"));
					if(isset($dateEntries[$date])){
						$tempArray = array(date("l M jS", strtotime($date)), floatval($dateEntries[$date]["hours"]));
						if(floatval($dateEntries[$date]["hours"]) > $maxInDay){
							$color = "#EDABAB";
						}else{
							$color = "#49BFF2";
						}
						array_push($res, array("color"=>$color, "label"=>$dateEntries[$date]["label"], "data"=>array($tempArray)));
					}else{
						$tempArray = array(date("l M jS", strtotime($date)), 0);
						array_push($res, array("color"=>"#49BFF2", "data"=>array($tempArray)));
					}
				}
			}
		break;
		case "month":
			$maxInDay = floatval($mysqli->query("SELECT max_in_day FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_in_day"]);
			$length = returnLengthOfMonth(date("n", strtotime($start))-1, date("L", strtotime($start)));
			$end = date("Y-m-d", strtotime($start." + $length days"));
			$query = "SELECT * FROM hours WHERE pilot_id=$user AND date >= '$start' AND date < '$end' ORDER BY date";
			$result = $mysqli->query($query);
			if($result != false){
				$dateEntries = array();
				while($row = $result->fetch_assoc()){
					if(!isset($dateEntries[$row["date"]]))
						$dateEntries[$row["date"]] = array("hours"=>$row["hours"], "label"=>$row["aircraft"]);
					else
						$dateEntries[$row["date"]]["hours"] += $row["hours"];
					$total += floatval($row["hours"]);
				}

				for($i = 0; $i < $length; $i++){
					$date = date("Y-m-d", strtotime($start." + $i days"));
					if(isset($dateEntries[$date])){
						$tempArray = array(date("M<\\b\\r/>jS", strtotime($date)), floatval($dateEntries[$date]["hours"]));
						if(floatval($dateEntries[$date]["hours"]) > $maxInDay){
							$color = "#EDABAB";
						}else{
							$color = "#49BFF2";
						}
						array_push($res, array("color"=>$color, "label"=>$dateEntries[$date]["label"], "data"=>array($tempArray)));
					}else{
						$tempArray = array(date("M<\\b\\r/>jS", strtotime($date)), 0);
						array_push($res, array("color"=>"#49BFF2", "data"=>array($tempArray)));
					}
				}
			}
		break;
		case "year":
			$max28 = floatval($mysqli->query("SELECT max_last_28 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_last_28"]);
			$end = date("Y-m-d", strtotime($start." + 1 year"));
			$query = "SELECT * FROM hours WHERE pilot_id=$user AND date >= '$start' AND date < '$end'";
			$result = $mysqli->query($query);
			if($result != false){
				$dateEntries = array();
				while($row = $result->fetch_assoc()){
					$dateEntries[$row["date"]] = array("hours"=>$row["hours"], "label"=>$row["aircraft"]);
					$total += floatval($row["hours"]);
				}
				$isLeapYear = date("L", strtotime($start));
				for($m = 0; $m < 12; $m++){
					$monthHrs = 0;
					for($i = 0; $i < returnLengthOfMonth($m, $isLeapYear); $i++){
						$d = $i+returnAcumDays($m, $isLeapYear);
						$date = date("Y-m-d", strtotime($start." + $d days"));
						if(isset($dateEntries[$date])){
							$monthHrs += floatVal($dateEntries[$date]["hours"]);
						}
					}
					if($monthHrs > $max28){
						$color = "#EDABAB";
					}else{
						$color = "#49BFF2";
					}
					array_push($res, array("color"=>$color, "data"=>array(array(date("F", strtotime($start." + $m months")), $monthHrs))));
				}
			}
		break;
		case "past7":
			$maxInDay = floatval($mysqli->query("SELECT max_in_day FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_in_day"]);
			$end = date("Y-m-d", strtotime($start." - 6 days"));
			$query = "SELECT * FROM hours WHERE pilot_id=$user AND `date` <= '$start' AND date >= '$end' ORDER BY date";
			// error_log("\nQuery: $query", 3, "../logs/log.txt");
			$result = $mysqli->query($query);
			if($result != false){
				$dateEntries = array();
				while($row = $result->fetch_assoc()){
					if(!isset($dateEntries[$row["date"]]))
						$dateEntries[$row["date"]] = array("hours"=>$row["hours"], "label"=>$row["aircraft"]);
					else
						$dateEntries[$row["date"]]["hours"] += $row["hours"];
					$total += floatval($row["hours"]);
				}
				// error_log("\ndateEntries: ".json_encode($dateEntries), 3, "../logs/log.txt");
				for($i = 6; $i >= 0; $i--){
					$date = date("Y-m-d", strtotime($start." - $i days"));
					if(isset($dateEntries[$date])){
						$tempArray = array(date("l M jS", strtotime($date)), floatval($dateEntries[$date]["hours"]));
						if(floatval($dateEntries[$date]["hours"]) > $maxInDay){
							$color = "#EDABAB";
						}else{
							$color = "#49BFF2";
						}
						array_push($res, array("color"=>$color, "label"=>$dateEntries[$date]["label"], "data"=>array($tempArray)));
					}else{
						$tempArray = array(date("l M jS", strtotime($date)), 0);
						array_push($res, array("color"=>"#49BFF2", "data"=>array($tempArray)));
					}
				}
			}
		break;
		case "past28":
			$maxInDay = floatval($mysqli->query("SELECT max_in_day FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_in_day"]);
			$end = date("Y-m-d", strtotime($start." - 27 days"));
			$query = "SELECT * FROM hours WHERE pilot_id=$user AND date <= '$start' AND date >= '$end' ORDER BY date";
			// error_log("\nQuery: $query", 3, "../logs/log.txt");
			$result = $mysqli->query($query);
			if($result != false){
				$dateEntries = array();
				while($row = $result->fetch_assoc()){
					if(!isset($dateEntries[$row["date"]]))
						$dateEntries[$row["date"]] = array("hours"=>$row["hours"], "label"=>$row["aircraft"]);
					else
						$dateEntries[$row["date"]]["hours"] += $row["hours"];
					$total += floatval($row["hours"]);
				}
				// error_log("\ndateEntries: ".json_encode($dateEntries), 3, "../logs/log.txt");
				for($i = 27; $i >= 0; $i--){
					$date = date("Y-m-d", strtotime($start." - $i days"));
					if(isset($dateEntries[$date])){
						$tempArray = array(date("M<\\b\\r/>jS", strtotime($date)), floatval($dateEntries[$date]["hours"]));
						if(floatval($dateEntries[$date]["hours"]) > $maxInDay){
							$color = "#EDABAB";
						}else{
							$color = "#49BFF2";
						}
						array_push($res, array("color"=>$color, "label"=>$dateEntries[$date]["label"], "data"=>array($tempArray)));
					}else{
						$tempArray = array(date("M<\\b\\r/>jS", strtotime($date)), 0);
						array_push($res, array("color"=>"#49BFF2", "data"=>array($tempArray)));
					}
				}
			}
		break;
	}

	$totalMin = round(($total - intval($total)) * 60);
	$totalString = intval($total).":".($totalMin > 9 ? $totalMin : '0'.$totalMin);
	print(json_encode(array("total"=>$total, "totalString"=>$totalString, "data"=>$res)));

function returnLengthOfMonth($month, $isLeap){
	switch($month){
		case 0: return 31; break;
		case 1: $d = ($isLeap ? 29 : 28);
			return $d;
		break;
		case 2: return 31; break;
		case 3: return 30; break;
		case 4: return 31; break;
		case 5: return 30; break;
		case 6: return 31; break;
		case 7: return 31; break;
		case 8: return 30; break;
		case 9: return 31; break;
		case 10: return 30; break;
		case 11: return 31; break;
	}
}

function returnAcumDays($month, $isLeap){
	switch($month){
		case 0: return 0; break;
		case 1: return 31; break;
		case 2: $d = ($isLeap ? 60 : 59); 
			return $d;
		break;
		case 3: $d = ($isLeap ? 91 : 90); 
			return $d;
		break;
		case 4: $d = ($isLeap ? 121 : 120); 
			return $d;
		break;
		case 5: $d = ($isLeap ? 152 : 151); 
			return $d;
		break;
		case 6: $d = ($isLeap ? 182 : 181); 
			return $d;
		break;
		case 7: $d = ($isLeap ? 213 : 212); 
			return $d;
		break;
		case 8: $d = ($isLeap ? 244 : 243); 
			return $d;
		break;
		case 9: $d = ($isLeap ? 274 : 273); 
			return $d;
		break;
		case 10: $d = ($isLeap ? 305 : 304); 
			return $d;
		break;
		case 11: $d = ($isLeap ? 335 : 334); 
			return $d;
		break;
	}
}
?>