<?php
	include_once "db_connect.php";
	$account = $_SESSION["account"];
	if(isset($_GET["category"]) && $_GET["category"] != "AllCategories"){
		$category = " AND d.category = '$_GET[category]'";
	}else{
		$category = "";
	}
	$query = "SELECT d.*, CONCAT(p.lname,', ',p.fname) AS creator_name FROM documents d INNER JOIN pilot_info p ON p.id=d.creator WHERE d.creator IN (SELECT id FROM pilot_info WHERE account=$account)$category ORDER BY d.timestamp DESC";
	$result = $mysqli->query($query);
	$res = array();
	if($result != false){
		while($row = $result->fetch_assoc()){
			$row['filename'] = $account.'/'.$row['filename'];
			$row["name"] = str_replace('_', ' ', substr($row["name"], 0, strrpos($row['name'], '-1')));
			array_push($res, $row);
		}
	}
	print(json_encode($res));
?>