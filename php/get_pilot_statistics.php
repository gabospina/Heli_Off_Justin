<?php
	include_once "db_connect.php";

	if (session_status() == PHP_SESSION_NONE) {
	      session_start();
	  }
	$user = $_SESSION["HeliUser"];
	$page = intval($_GET["page"]);
	$date = $_GET["start"];
	$totals = intval($mysqli->query("SELECT COUNT(id) AS total FROM hours WHERE pilot_id=$user AND `date` >= '$date'")->fetch_assoc()["total"]);
	if(isset($_GET["init"]) && $totals > 0){
		$page = ceil($totals/18)-1;
	}
	$offset = $page*18;
	$query = "SELECT * FROM hours WHERE pilot_id=$user AND `date` >= '$date' ORDER BY `date` ASC LIMIT 18 OFFSET $offset";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			$min = round(($row["hours"]-intval($row["hours"]))*60);
			$row["hoursString"] = intval($row["hours"]).":".($min > 9 ? $min : '0'.$min);
			array_push($res, $row);
		}
	}else{
		print($query." || ".$mysqli->error);
	}
	print(json_encode(array("entries"=>$res, "total"=>$totals)));
?>