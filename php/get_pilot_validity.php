<?php
	include_once "db_connect.php";
	include_once "validity.php";
	
	$valHelper = new Validity();
	$validity = $valHelper->getAllFieldsAndValues($_SESSION['HeliUser']);
	if($validity === false) {
		print(json_encode(array('error'=> $valHelper->getError())));
	}else {
		print(json_encode($validity));
	}
?>