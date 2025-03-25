<?php
	include_once "db_connect.php";
	$account = $_SESSION["account"];
	
	//insert into document views if record doesn't exist already
	$documentViews = $mysqli->query("SELECT pilot_id FROM document_views WHERE doc_id=$_GET[id] AND pilot_id=$_SESSION[HeliUser]")->num_rows;
	if($documentViews == 0){
		$mysqli->query("INSERT INTO document_views VALUES ($_GET[id], $_SESSION[HeliUser])");
	}

	$query = "SELECT d.*, CONCAT(p.lname,', ',p.fname) AS creator_name FROM documents d INNER JOIN pilot_info p ON p.id=d.creator WHERE d.id=$_GET[id]";
	$result = $mysqli->query($query);
	$res = "";
	if($result != false){
		$res = $result->fetch_assoc();
		$res['filename'] = $account.'/'.$res['filename'];
		$res["name"] = str_replace('_', ' ', substr($res["name"], 0, strrpos($res['name'], '-1')));
	}
	print(json_encode($res));
