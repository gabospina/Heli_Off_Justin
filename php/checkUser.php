<?php
	session_start();
	if(!isset($_SESSION["uid"]) || $_SESSION["uid"] == null){
		print("false");
	}else{
		print_r($_SESSION["uid"]);
	}
?>