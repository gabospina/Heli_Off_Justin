<?php
	include_once 'db_connect.php';
	$account = $_SESSION["account"];
	$result = $mysqli->query("SELECT DISTINCT category FROM documents WHERE creator IN (SELECT id FROM pilot_info WHERE account=$account) AND category != ''");
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			array_push($res, $row["category"]);
		}
	}
	print(json_encode($res));
?>