<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$account = $_SESSION["account"];
	$query = "SELECT DISTINCT craft FROM crafts WHERE account=$account";
	$result = $mysqli->query($query);
	if($result != FALSE){
		$crafts = array();
		$i = 0;
		while($row = mysqli_fetch_assoc($result)){
			$crafts[$i] = $row["craft"];
			$i++;
		}
		$contract = array();
		$default = (isset($crafts[0]) ? $crafts[0] : "");
		$query = "SELECT cr.id AS craft_id, cr.class AS class, cr.tod AS TOD, coni.name AS contract FROM crafts cr INNER JOIN contracts con ON con.craftid=cr.id INNER JOIN contract_info coni ON coni.id=con.contract_id WHERE craft='$default' AND cr.account=$account AND coni.account=$account ORDER BY cr.class";
		$result = $mysqli->query($query);
		if($result != FALSE){
			$classes = array();
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$classes[$i] = array("id"=>$row['craft_id'], "class"=>$row["class"], "tod"=>$row["TOD"]);
				$contract[$i] = $row["contract"];
				$i++;
			}

			$res = array("crafts"=>$crafts, "contract"=>$contract, "classes"=>$classes);
			print_r(json_encode($res));
		}else{
			print("false");
		}
	}else{
		print("false");
	}
?>