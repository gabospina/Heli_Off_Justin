<?php
	include_once "db_connect.php";

	$user = $_SESSION["HeliUser"];
	$query = "SELECT DISTINCT aircraft FROM craft_experience WHERE pilot_id=$user";
	$result = $mysqli->query($query);
	$res = array();
	$grandTotal = 0;
	if($result != false){
		while($row = $result->fetch_assoc()){
			$comResult = $mysqli->query("SELECT SUM(hours) AS total FROM craft_experience WHERE aircraft='$row[aircraft]' AND pilot_id=$user AND position='com'");
			if($comResult !== FALSE && $comResult->num_rows > 0) {
				$comTotal = floatval($comResult->fetch_assoc()["total"]);
				$comMin = round(($comTotal-intval($comTotal))*60);
				$comString = intval($comTotal).':'.($comMin > 9 ? $comMin : '0'.$comMin);
				$res[$row["aircraft"]]["com"] = array('string'=>$comString, 'value'=>$comTotal);
			}else {
				$res[$row["aircraft"]]["com"] = '';
			}

			$pilResult = $mysqli->query("SELECT SUM(hours) AS total FROM craft_experience WHERE aircraft='$row[aircraft]' AND pilot_id=$user AND position='pil'");
			if($pilResult !== FALSE && $pilResult->num_rows > 0) {
				$pilTotal = floatval($pilResult->fetch_assoc()["total"]);
				$pilMin = round(($pilTotal-intval($pilTotal))*60);
				$pilString = intval($pilTotal).':'.($pilMin > 9 ? $pilMin : '0'.$pilMin);
				$res[$row["aircraft"]]["pil"] = array('string'=>$pilString, 'value'=>$pilTotal);
			}else {
				$res[$row["aircraft"]]["pil"] = '';
			}
		}
	}
	print(json_encode($res));
?>