<?php
	include_once "db_connect.php";
	$account = $_SESSION["account"];
	$query = "SELECT DISTINCT id, name, color FROM contract_info WHERE account=$account ORDER BY `order`";
	$result = $mysqli->query($query);
	if($result != FALSE){
		$i = 0;
		$res = array();
		while($row = mysqli_fetch_assoc($result)){
			$res["id"][$i] = $row["id"];
			$res["name"][$i] = $row["name"];
			$res["color"][$i] = $row["color"];
			$i++;
		}
		print_r(json_encode($res));
	}else{
		print("false");
	}
?>