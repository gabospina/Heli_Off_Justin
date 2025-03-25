<?php
	include_once "db_connect.php";
	include_once 'validity.php';
	$user = (isset($_GET["pilot_id"]) ? $_GET["pilot_id"] : $_SESSION["HeliUser"]);
	$test = $_GET["test"];
	$account = $_SESSION['account'];
	$valHelper = new Validity();
	$res = $valHelper->getValidityDocuments($test, $user);
	if($res === false) {
		print('[]');
	}else {
		print(json_encode($res));
	}
?>