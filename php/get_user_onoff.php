<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$id = $_SESSION["HeliUser"];
	$cur = date("Y-m-d");
	$query = "SELECT * FROM available WHERE id=$id AND `off`>='$cur' ORDER BY `on` ASC";
	$result = $mysqli->query($query);
	if($result != FALSE){
		$res = array();
		$i = 0;
		while($row = mysqli_fetch_assoc($result)){
			$res["on"][$i] = $row["on"];
			$res["off"][$i] = $row["off"];
			$i++;
		}
		print_r(json_encode($res));
	}else{
		print("false");
	}
?>