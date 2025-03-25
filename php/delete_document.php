<?php
	include_once "db_connect.php";

	$id = $_POST["id"];
	$filename = $_POST["filename"];

	if(file_exists("../../uploads/documents/$filename")){
		unlink("../../uploads/documents/$filename");
	}

	if($mysqli->query("DELETE FROM documents WHERE id=$id")){
		print("success");
	}else{
		print("failed: ".$mysqli->error);
	}

?>