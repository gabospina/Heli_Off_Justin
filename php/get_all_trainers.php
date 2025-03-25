<?php
	include_once "db_connect.php";
	$account = $_SESSION["account"];
	$query = "SELECT p.id, CONCAT(p.lname,', ',p.fname) AS name, p.training FROM pilot_info p WHERE (p.training = 1 OR p.training = 2) AND p.account=$account";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			array_push($res, $row);
		}
	}
	print(json_encode($res));
?>