<?php
	if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION["lastHeliSelected"] = $_POST["craft"];
?>