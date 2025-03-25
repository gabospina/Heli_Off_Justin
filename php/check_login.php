<?php
	if (session_status() == PHP_SESSION_NONE) {
	  session_start();
	}
	// if(isset($_SESSION["HeliUser"]) && isset($_SESSION["expire"]) && $_SESSION["expire"] >= time()){
	// 	$_SESSION["expire"] = (time()+(3*60*60));
	// }else{
	// 	header("Location: ../../index.php");
	// }
?>