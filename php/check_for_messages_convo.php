<?php
	include_once "db_connect.php";

	$user = $_SESSION["HeliUser"];
	$pk = $_GET["pk"];
	$latest = $_GET["latest"];

	$query = "SELECT CONCAT(p.fname,' ',p.lname) AS sender, tm.sender_id, tm.timestamp, tm.type, tm.message FROM thread_messages tm INNER JOIN pilot_info p ON p.id=tm.sender_id WHERE tm.thread_id=$pk AND tm.timestamp > $latest ORDER BY tm.timestamp ASC";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		$mysqli->query("UPDATE thread_users SET `read`=1 WHERE thread_id=$pk AND user_id=$user");
		while($row = $result->fetch_assoc()){
			if($row["sender_id"] == $user){
				$type = "sent";
			}else{
				$type = "received";
			}
			if($row["type"] == "text"){
				array_push($res, array("type"=>$type, "value"=>$row["message"], "name"=>$row["sender"], "message_type"=>$row["type"], "timestamp"=>$row["timestamp"]));
			}elseif($row["type"] == "attachment"){
				$attachQuery = "SELECT * FROM thread_attachments WHERE attachment_id=$row[message]";
				$attachResult = $mysqli->query($attachQuery);
				if($attachResult != false){
					$attachRow = $attachResult->fetch_assoc();
					array_push($res, array("type"=>$type, "value"=>$attachRow["file_name"], "file_size"=>$attachRow["file_size"], "file_type"=>$attachRow["file_type"], "name"=>$row["sender"], "message_type"=>$row["type"], "timestamp"=>$row["timestamp"]));
				}
			}
		}
	}else{
		print($query." || ".$mysqli->error);
	}

	print(json_encode($res));
?>