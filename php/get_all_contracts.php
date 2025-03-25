<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	$account = $_SESSION["account"];
	$query = "SELECT coi.id AS contractid, coi.name AS contract, co.craftid AS craftid, coi.color AS color, cr.id AS craft_id, cr.craft AS craft, cr.class AS class FROM crafts cr INNER JOIN contracts co ON co.craftid=cr.id INNER JOIN contract_info coi ON coi.id=co.contract_id WHERE coi.account=$account AND cr.account=$account ORDER BY coi.order";
	$result = $mysqli->query($query);

	if($result != FALSE){
		$res = array();
		while($row = mysqli_fetch_assoc($result)){
			array_push($res, $row);
		}
		print_r(json_encode($res));
	}else{
		print("false");
	}
?>