<?php
	include_once "db_connect.php";
	$account = $_SESSION["account"];
	$query = "SELECT * FROM training_available WHERE account=$account";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			array_push($res, $row);
		}
	}
	print(json_encode($res));

?>