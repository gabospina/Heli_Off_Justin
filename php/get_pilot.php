<?php
	include_once "db_connect.php";
	include_once "validity.php";

	// if($_SESSION["admin"] == 1){
		if($_POST["id"] == "user"){
			$pilid = $_SESSION["HeliUser"];
		}else{
			$pilid = $_POST["id"];
		}
		$query = "SELECT p.*, l.name, l.admin FROM pilot_info p INNER JOIN login l ON l.id=p.id WHERE p.id=$pilid";
		$result = $mysqli->query($query);
		if($result != FALSE){
			$res = array();
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$res["id"][$i] = $row["id"];
				$res["admin"][$i] = $row["admin"];
				$res["fname"][$i] = $row["fname"];
				$res["lname"][$i] = $row["lname"];
				$res["username"][$i] = $row["name"];
				$res["comandante"][$i] = $row["comandante"];
				$res["nationality"][$i] = $row["nationality"];
				$res["ang_license"][$i] = $row["ang_license"];
				$res["for_license"][$i] = $row["for_license"];
				$res["email"][$i] = $row["email"];
				$res["phone"][$i] = $row["phone"];
				$res["phonetwo"][$i] = $row["phonetwo"];
				$res["contracts"][$i] = $row["contracts"];
				$res["crafts"][$i] = $row["crafts"];
				$res["training"][$i] = $row["training"];
				$res["profile_picture"][$i] = $row["profile_picture"];
				$i++;
			}
			$cur = date_create(date("Y-m-d"));
			for($i = 0; $i < count($res["id"]); $i++){
				$id = $res["id"][$i];
				
				$valHelper = new Validity();
				$validity = $valHelper->getAllFieldsAndValues($id);

				$statuses = $valHelper->getValidityStatuses();
				$res["allValid"][$i] = $statuses["all_valid"];
				$res["monthValid"][$i] = $statuses["validity_within_month"];
				$res["validity"][$i] = $validity;
			}
			
			for($i = 0; $i < count($res["id"]); $i++){
				$id = $res["id"][$i];
				$query = "SELECT `on`, `off` FROM available WHERE id=$id";
				$result = $mysqli->query($query);
				$onOffAr = array();
				if($result != FALSE){
					while($row = mysqli_fetch_assoc($result)){
						$on = $row["on"];
						$off = $row["off"];
						if(strtotime($on) < strtotime(date("Y-m-d"))){
							$checkOn = date("Y-m-d");
						}else{
							$checkOn = $on;
						}
						
						$inSchedSql = "SELECT DISTINCT id FROM schedule WHERE id=$id AND sched_date>='$checkOn' AND sched_date<='$off'";
						$inSchedRes = $mysqli->query($inSchedSql);
						$numrows = mysqli_num_rows($inSchedRes);

						if($inSchedRes != FALSE &&  $numrows > 0){
							$inSched = true;
						}else{
							$inSched = false;
						}
						$tempAr = array("on"=>$on, "off"=>$off, "inSched"=>$inSched);
						array_push($onOffAr, $tempAr);
					}
				}
				$res["onOff"][$i] = $onOffAr;
			}
			print_r(json_encode($res));
		}else{
			print_r(json_encode(array("success"=>false, "msg"=>"Couldn't access pilot info")));
		}
	// }else{
	// 	print_r(json_encode(array("success"=>false, "msg"=>"Not Admin")));
	// }

	$mysqli->close();
?>