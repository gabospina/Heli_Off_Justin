<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$account = $_SESSION["account"];
	if(isset($_GET["distinct"])){
		$query = "SELECT DISTINCT craft FROM crafts WHERE account=$account";
	}else{
		$query = "SELECT * FROM crafts WHERE account=$account";
	}
	$result = $mysqli->query($query);
	if($result != FALSE){
		$res = array();
		$i = 0;
		while($row = mysqli_fetch_assoc($result)){
			if(isset($_GET["distinct"])){
				$res[$i] = $row["craft"];
			}else{
				array_push($res, $row);
			}
			$i++;
		}
		print_r(json_encode($res));
	}else{
		print("false");
	}
?>