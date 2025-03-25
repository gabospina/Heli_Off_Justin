<?php
	include_once "db_connect.php";
	$pks = json_decode($_POST["pks"], true);
	$user = $_SESSION["HeliUser"];
	array_push($pks, $user);
	//check if there's any threads already made with these exact users
	$query = "SELECT thread_id FROM thread_users WHERE ";
	$users = "(";
	for($i = 0; $i < count($pks); $i++){
		$query .= ($i != 0 ? " AND ": "")."thread_id IN (SELECT thread_id FROM thread_users WHERE user_id=$pks[$i])";
		$users .= ($i != 0 ? "," : "").$pks[$i];
	}
	$users .= ")";
	$query .= " AND thread_id NOT IN (SELECT thread_id FROM thread_users WHERE user_id NOT IN $users)";
	$result = $mysqli->query($query);
	//if there is, use this thread ID
	if($result->num_rows != 0){
		print($result->fetch_assoc()["thread_id"]);
	//else create thread and add users to it
	}else{
		$time = time();
		//create thread
		$query = "INSERT INTO threads VALUES (null, $time)";
		$mysqli->query($query);
		$thread_id = $mysqli->insert_id;
		$errors = "";
		for($i = 0; $i < count($pks); $i++){
			//add users to the thread
			$query = "INSERT INTO thread_users VALUES($thread_id, $pks[$i], 1)";
			if(!$mysqli->query($query))
				$errors.=" ".$mysqli->error;
		}
		if($errors == "")
			print($thread_id);
		else{
			print($errors);
		}
	}
?>