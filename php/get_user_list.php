<?php
	include_once "db_connect.php";

	$user = $_SESSION["HeliUser"];
	$account = $_SESSION["account"];
	$query = "SELECT p.id, CONCAT(p.fname,' ',p.lname) AS name, p.comandante, p.contracts, p.crafts, p.training, l.admin FROM pilot_info p INNER JOIN login l ON l.id=p.id WHERE p.account=$account AND p.id!=$user ORDER BY p.fname";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			$row["permissions"] = returnPermissions(intval($row["admin"]));
			$row["training"] = returnTraining($row["training"]);
			$row["position"] = ($row["comandante"] == 1 ? "comandante" : "piloto");
			array_push($res, $row);
		}
	}
	print(json_encode($res));

	function returnPermissions($admin){
		switch($admin){
			case 0: return "pilot"; break;
			case 1: return "schedule manager pilot"; break;
			case 2: return "training manager pilot"; break;
			case 3: return "manager pilot"; break;
			case 4: return "schedule manager"; break;
			case 5: return "training manager"; break;
			case 6: return "manager"; break;
			case 7: return "admin"; break;
			case 8: return "admin pilot"; break;
		}
	}

	function returnTraining($training){
		switch($training){
			case 0: return ""; break;
			case 1: return "tri"; break;
			case 2: return "tre"; break;
		}
	}
?>