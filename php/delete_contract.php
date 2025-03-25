<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$contractName = $_POST["contractName"];
	$contractID = $_POST["contractID"];
	$account = $_SESSION["account"];
	$sql = "DELETE FROM contract_info WHERE id='$contractID'";
	$delete = $mysqli->query($sql);
	$sql = "DELETE FROM contracts WHERE contract_id=$contractID";
	if($delete){
		$query = "SELECT id, contracts FROM pilot_info WHERE contracts LIKE '%$contractName%' AND account=$account";
		$result = $mysqli->query($query);
		if($result != FALSE){
			$success = true;
			while($row = mysqli_fetch_assoc($result)){
				$id = $row["id"];
				$contractRes = $row["contracts"];
				$contractStr = str_replace($contractName.";", "", $contractRes);
				$sql = "UPDATE pilot_info SET contracts='$contractStr' WHERE id=$id";
				$update = $mysqli->query($sql);
				if(!$update){
					$success = false;
				}
			}
			if($success){
				print("success");
			}else{
				print("failed update");
			}
		}else{
			print("failed query");
		}
	}else{
		print("failed delete");
	}
?>