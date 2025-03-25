<?php
	include_once "db_connect.php";

	$id = $_POST["pk"];

	$query = "DELETE FROM hours WHERE id=$id";
	if($mysqli->query($query)){
		print("success");
	}else{
		print($mysqli->error);
	}
?>