<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$craft = $_POST["craft"];
	$class = $_POST["class"];
	$tod = $_POST["tod"];
	$alive = $_POST["alive"];
	$account = $_SESSION["account"];

	$sql = "INSERT INTO crafts VALUES(null, '$craft', '$class', '$tod', $alive, $account)";
	$insert = $mysqli->query($sql);

	if($insert){
		print("success;".$mysqli->insert_id);
	}else{
		print("failed");
	}
?>