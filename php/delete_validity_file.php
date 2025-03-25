<?php
	include_once "db_connect.php";
	$user = (isset($_POST["pilot_id"]) ? $_POST["pilot_id"] : $_SESSION["HeliUser"]);
	$account = $_SESSION['account'];
	$test = $_POST["test"];
	$file = $_POST["filename"];

	if(file_exists("../../uploads/validity/$account/$user/$test/$file")){
		unlink("../../uploads/validity/$account/$user/$test/$file");
	}
?>