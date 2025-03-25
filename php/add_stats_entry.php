<?php
	include_once "db_connect.php";

	if (session_status() == PHP_SESSION_NONE) {
	      session_start();
	  }
	$user = $_SESSION["HeliUser"];
	$date = $_POST["date"];
	$model = $_POST["model"];
	$craft = $_POST["craft"];
	$command = $_POST["command"];
	$copilot = $_POST["copilot"];
	$route = $mysqli->real_escape_string($_POST["route"]);
	$ifr = $_POST["ifr"];
	$actual = $_POST["actual"];
	$dayHours = $_POST["dayHours"];
	$nightHours = $_POST["nightHours"];

	$ids = array();
	$errors = "";
	$totalHours = 0;
	if($dayHours != ""){
		$hrs = $dayHours;
		if(strpos($dayHours, ":") !== false){
			$hrsAr = explode(":", $dayHours);
			$hrs = floatval($hrsAr[0])+(floatval($hrsAr[1])/60);
		}else{
			$hrs = floatval($dayHours);
		}
		$totalHours += $hrs;
		$query = "INSERT INTO hours VALUES(null, $user, \"$date\", $hrs, \"day\", \"$model\", \"$craft\", \"$command\", \"$copilot\", \"$route\", $ifr, $actual)";
		$result = $mysqli->query($query);
		if($result == false){
			$errors.=$mysqli->error;
		}
		array_push($ids, array("id"=>$mysqli->insert_id, "type"=>"Day", "hours"=>$dayHours));
	}
	if($nightHours != ""){
		$hrs = $nightHours;
		if(strpos($nightHours, ":") !== false){
			$hrsAr = explode(":", $nightHours);
			$hrs = floatval($hrsAr[0])+(floatval($hrsAr[1])/60);
		}else{
			$hrs = floatval($nightHours);
		}
		$totalHours += $hrs;
		$query = "INSERT INTO hours VALUES(null, $user, \"$date\", $hrs, \"night\", \"$model\", \"$craft\", \"$command\", \"$copilot\", \"$route\", $ifr, $actual)";
		$result = $mysqli->query($query);
		if($result == false){
			$errors.=$mysqli->error;
		}
		array_push($ids, array("id"=>$mysqli->insert_id, "type"=>"Night", "hours"=>$nightHours));
	}
	
	//TODO find out if I'm command or copilot
	if(!$mysqli->query("INSERT INTO craft_experience VALUES (null, $user, '$model', '$_POST[pos]', $totalHours)")){
		$errors .= $mysqli->error;
	}
	if($errors == ""){
		print("success=".json_encode($ids));
	}else{
		print($errors);
	}
	
?>