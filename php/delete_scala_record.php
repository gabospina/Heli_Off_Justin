<?php
	include_once "db_connect.php";

	$id = $_POST["id"];

	$delete = "DELETE FROM scala_records WHERE id=$id";
	if($mysqli->query($delete)){
		print("success");
	}else{
		print($mysqli->error);
	}

?>