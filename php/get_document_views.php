<?php
	include_once "db_connect.php";

	$query = "SELECT CONCAT(p.lname,', ',p.fname) AS name FROM pilot_info p INNER JOIN document_views dv ON dv.pilot_id=p.id WHERE dv.doc_id=$_GET[id] ORDER BY p.lname";
	$result = $mysqli->query($query);
	$res = array();
	$res["viewed"] = array();
	$res["notviewed"] = array();
	while($row = $result->fetch_assoc()){
		array_push($res["viewed"], $row["name"]);
	}
	$account = $_SESSION["account"];
	$query = "SELECT CONCAT(lname,', ',fname) AS name FROM pilot_info WHERE id NOT IN (SELECT pilot_id FROM document_views WHERE doc_id=$_GET[id]) AND account=$account ORDER BY lname";
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		array_push($res["notviewed"], $row["name"]);
	}

	print(json_encode($res));
?>