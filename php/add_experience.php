<?php
	include_once "db_connect.php";
	$hrs = $_POST["hours"];
	if(strpos($hrs, ":") !== false){
		$hrsAr = explode(":", $hrs);
		$hrs = floatval($hrsAr[0])+(floatval($hrsAr[1])/60);
	}else{
		$hrs = floatval($hrs);
	}
	if($mysqli->query("INSERT INTO craft_experience VALUES (null, $_SESSION[HeliUser], '$_POST[aircraft]', '$_POST[position]', $hrs)")){
		print("success");
	}else{
		print($mysqli->error);
	}

?>