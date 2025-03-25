<?php
	include_once "db_connect.php";
	$user = $_SESSION["HeliUser"];
	$craft = $_POST["craft"];

	if($mysqli->query("DELETE FROM craft_experience WHERE pilot_id=$user AND aircraft='$craft'")){
		print("success");
	}else{
		print($mysqli->error);
	}
?>