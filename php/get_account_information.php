<?php
	include_once "db_connect.php";
	$account = $_SESSION["account"];
	$query = "SELECT a.name, ai.* FROM account a INNER JOIN account_info ai ON ai.id=a.id WHERE a.id=$account";
	$result = $mysqli->query($query);
	if($result != false){
		print(json_encode($result->fetch_assoc()));
	}else{
		print($mysqli->error);
	}
?>