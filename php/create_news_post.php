<?php
	include_once "db_connect.php";
	if(intval($_SESSION["HeliUser"]) == 12){
		$title = $_POST["title"];
		$message = $mysqli->real_escape_string(nl2br($_POST["message"]));
		$image = $_POST["image"];
		$link = $_POST["link"];

		if($image == ""){
			$image = "null";
		}else{
			$image = "'".$image."'";
		}

		if($link == ""){
			$link = "null";
		}else{
			$link = "'".$link."'";
		}

		$ts = time();
		$query = "INSERT INTO news VALUES (null, $ts, '$title', '$message', $image, $link)";
		if($mysqli->query($query)){
			$insertID = $mysqli->insert_id;
			$result = $mysqli->query("SELECT id FROM pilot_info");
			while($row = $result->fetch_assoc()){
				$mysqli->query("INSERT INTO news_notifications VALUES (null, $row[id], $insertID)");
			}
			print("success");
		}
		else{
			print("failed Query: $query  : ".$mysqli->error);
		}
	}	
?>