<?php
	include_once "db_connect.php";
	$date = $_GET["date"];
	$account = $_SESSION["account"];
	$query = "SELECT * FROM sms_messages WHERE sched_date='$date' AND account=$account";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			array_push($res, $row);
		}
	}
	print(json_encode($res));
?>