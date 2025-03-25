<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$id = $_POST["id"];
	$sql = "DELETE FROM login WHERE id=$id";
	$delete = $mysqli->query($sql);
	if($delete){
		$sql = "DELETE FROM schedule WHERE id=$id";
		$delete = $mysqli->query($sql);
		if($delete){
			$sql = "DELETE FROM pilot_info WHERE id=$id";
			$delete = $mysqli->query($sql);
			if($delete){
				$sql = "DELETE FROM validity WHERE id=$id";
				$delete = $mysqli->query($sql);
				if($delete){
					$success = true;
				}else{
					$sucess = false;
				}
			}else{
				$sucess = false;
			}
		}else{
			$sucess = false;
		}
	}else{
		$sucess = false;
	}

	if($success){
		print("success");
	}else{
		print("failed");
	}
?>