<?php
	include_once "db_connect.php";

	$color = $_POST["color"];
	$id = $_POST["id"];

	$update = "UPDATE contract_info SET color='$color' WHERE id='$id'";
	if($mysqli->query($update)){
		print("success");
	}else{
		print($mysqli->error);
	}
?>