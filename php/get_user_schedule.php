<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	$user = $_SESSION["HeliUser"];
	$start = $_POST["start"];
	$end = $_POST["end"];
	$sql = "SELECT s.*, c.class AS craft_class, c.id AS craft_id FROM schedule s INNER JOIN crafts c ON c.id = s.craft WHERE sched_date BETWEEN '$start' AND '$end' AND s.id=$user";
	$result1 = $mysqli->query($sql);
	$res = array();
	if($result1 != FALSE){
		$i = 0;
		while($row = mysqli_fetch_assoc($result1)){
			$d = $row["sched_date"];
			$res["date"][$i] = $d;
			$c = $row["craft_id"];
			$res["craft"][$i] = $row["craft_class"];
			$res["pos"][$i] = $row["pos"];
			
			$sql2 = "SELECT pilot_info.lname AS lname FROM pilot_info, schedule WHERE schedule.sched_date='$d' AND craft=$c AND schedule.id!=$user AND pilot_info.id=schedule.id";
			$result2 = $mysqli->query($sql2);
			if($result2 != FALSE && mysqli_num_rows($result2) != 0){
				$row2 = mysqli_fetch_assoc($result2);
				$res["otherPil"][$i] = $row2["lname"];
			}else if($result2 != FALSE){
				$res["otherPil"][$i] = "";
			}
			$i++;
		}

		$backTen = date("Y-m-d", strtotime($start." - 10 days"));
		$sql = "SELECT * FROM training_schedule WHERE `date` BETWEEN '$backTen' AND '$end' AND (pilot_id1=$user OR pilot_id2=$user OR pilot_id3=$user OR pilot_id4=$user OR tri1_id=$user OR tri2_id=$user OR tre_id=$user)";
		$result = $mysqli->query($sql);
		if($result !== FALSE && $result->num_rows > 0){
			$i = 0;
			$row = $result->fetch_assoc();
			$d = $row["date"];
			if($row["pilot_id1"] == $user || $row["pilot_id2"] == $user || $row["pilot_id3"] == $user || $row["pilot_id4"] == $user){
				$trainingType = "trainee";
			}else if($row["tri1_id"] == $user || $row["tri2_id"] == $user){
				$trainingType = "tri";
			}else if($row["tre_id"] == $user){
				$trainingType = "tre";
			}
			for($k = 0; $k < $row["length"]; $k++){
				//if training start date + 7 days is within the user schedule dates, add it to the array.
				if(strtotime($d." + $k days") >= strtotime($start) && strtotime($d." + $k days") <= strtotime($end)){
					if($trainingType == "trainee"){
						$res["training_date"][$i] = date("Y-m-d", strtotime($d." + $k days"));
						$res["training_type"][$i] = $trainingType;

						$res["isExam"][$i] = ($trainingType == "tre" ? "Exam" : "Training");
						$res["training"][$i] = true;
						$res["training_craft"][$i] = $row["craft"];
						$i++;
					}elseif($trainingType == "tri" && $k != (intval($row["length"]) - 1)){
						$res["training_date"][$i] = date("Y-m-d", strtotime($d." + $k days"));
						$res["training_type"][$i] = $trainingType;

						$res["isExam"][$i] = ($trainingType == "tre" ? "Exam" : "Training");
						$res["training"][$i] = true;
						$res["training_craft"][$i] = $row["craft"];
						$i++;
					}elseif($trainingType == "tre" && $k == (intval($row["length"]) - 1)){
						$res["training_date"][$i] = date("Y-m-d", strtotime($d." + $k days"));
						$res["training_type"][$i] = $trainingType;

						$res["isExam"][$i] = ($trainingType == "tre" ? "Exam" : "Training");
						$res["training"][$i] = true;
						$res["training_craft"][$i] = $row["craft"];
						$i++;
					}
				}
			}
		}	
	}
	print_r(json_encode($res));

	mysqli_close($mysqli);

function withinDate($start, $end, $event, $length){

}
?>