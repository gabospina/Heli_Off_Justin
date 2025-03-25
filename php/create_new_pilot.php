<?php

	include_once "db_connect.php";
	include_once "mailto.php";

	$inputs = json_decode($_POST["inputs"], true);
	// $onOff = json_decode($_POST["onOff"], true);

	// if($_POST["contracts"] == ""){
		$contracts = 'null';
	// }else{
		// $contracts = "'".$_POST["contracts"]."'";
	// }

	// if($_POST["crafts"] == ""){
		$crafts = 'null';
	// }else{
	// 	$crafts = "'".$_POST["crafts"]."'";
	// }

	//replace with randomeStr() when using emails

	$temppass = $inputs["fname"].$inputs["lname"];
	$pass = hash("sha512", $temppass);
	$account = $_SESSION["account"];

	$fname = $inputs["fname"];
	$lname = $inputs["lname"];
	$user = $inputs["addUsername"];
	$admin = $inputs["admin"];
	
	$query = "SELECT name FROM login WHERE name='$user'";
	$result = $mysqli->query($query);

	//user doesn't already exist
	if($result != FALSE && mysqli_num_rows($result) == 0){
		$sql = "INSERT INTO login VALUES(null,'$user','$pass','$admin')";
		$result = $mysqli->query($sql);
		if($result){
			$id = $mysqli->insert_id;
			$comandante = $inputs["comandante"];
			$nationality = $inputs["nationality"];
			$ang_license = $inputs["ang_license"];
			$for_license = $inputs["for_license"];
			$email = ($inputs["email"] == "" ? "example@example.com" : $inputs["email"]);
			$phone = $inputs["phone"];
			$phonetwo = $inputs["phonetwo"];
			$training = $inputs["training"];
			$sql = "INSERT INTO pilot_info VALUES($id, '$fname', '$lname', '$comandante', '$nationality', '$ang_license', '$for_license', '$email', '$phone', '$phonetwo', null, null, $training, '', '', $account, null)";

			$result = $mysqli->query($sql);
			if($result){
				print("success $id");
			}else{
				print(mysqli_error($mysqli)." :::: ".$id);
			}
		}else{
			print("failed to create login");
		}
	}else{
		print("exists");
	}
	$mysqli->close();

	function randomStr(){
		return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789!@#$%^&*(){}[]?/.,"), 0, 11);
	}

?>