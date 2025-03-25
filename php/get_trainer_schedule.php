<?php
	include_once "db_connect.php";
	
	$start = $_GET["start"];
	$end = $_GET["end"];
	$account = $_SESSION["account"];
	
	$query = "SELECT tr.*, DATE_ADD(tr.date, INTERVAL tr.length DAY) AS end, CONCAT(p.lname, ' ', p.fname) AS name FROM trainer_schedule tr 
	INNER JOIN pilot_info p ON p.id=tr.pilot_id WHERE tr.date BETWEEN '$start' AND '$end' OR DATE_ADD(tr.date, INTERVAL tr.length DAY) BETWEEN '$start' 
	AND '$end' AND p.account=$account";
	$res = array();
	$result = $mysqli->query($query);
	if($result != false){
		while($row = $result->fetch_assoc()){
			array_push($res, $row);
		}
		print(json_encode($res));
	}else{
		print($mysqli->error);
	}
?>