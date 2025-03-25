<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$uid = $_SESSION["HeliUser"];
	$newpass = $_POST["pass"];
	$old = $_POST["old"];
	$query = "SELECT admin FROM login WHERE id=$uid AND password='$old'";
	$result = $mysqli->query($query);
	if($result != FALSE){
		if(mysqli_num_rows($result) == 1){
			$sql = "UPDATE login SET password='$newpass' WHERE id=$uid";
			$result = $mysqli->query($sql);
			if($result){
				print("success");
			}else{
				print("failed");
			}
		}else{
			print("Your old password is incorrect. Please try again.");
		}
	}else{
		print("failed");
	}
		
?>