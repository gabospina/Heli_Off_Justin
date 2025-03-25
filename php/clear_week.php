<?php
	include_once "db_connect.php";

	$start = $_POST["start"];
	$end = $_POST["end"];
	$crafts = $_POST["crafts"];

	$delete = "DELETE FROM schedule WHERE sched_date>='$start' AND sched_date<='$end' AND craft IN $crafts";
	$result = $mysqli->query($delete);
	if($result){
		print("success");
	}else{
		print($mysqli->error);
	}
?>