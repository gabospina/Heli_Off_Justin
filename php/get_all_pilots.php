<?php
	include_once "db_connect.php";
	include_once "validity.php";

	if(isset($_GET["showNonPilots"])){
		$showNonPilots = $_GET["showNonPilots"];
		if($showNonPilots == "f"){
			$adminCheck = "AND l.admin IN (0,1,2,3,8)";
		}elseif($showNonPilots == "m"){
			$adminCheck = "AND l.admin > 0";
		}else{
			$adminCheck = "";
		}
	}else{
		$adminCheck = "AND l.admin IN (0,1,2,3,8)";
	}
		
	// if($_SESSION["admin"] == 1){
		$account = $_SESSION["account"];
		$query = "SELECT p.*, l.admin FROM pilot_info p INNER JOIN login l ON l.id=p.id WHERE p.account=$account $adminCheck";
		$result = $mysqli->query($query);
		if($result != FALSE){
			$res = array();
			$i = 0;
			$res["id"] = array();
			while($row = mysqli_fetch_assoc($result)){
				$res["id"][$i] = $row["id"];
				$res["fname"][$i] = $row["fname"];
				$res["lname"][$i] = $row["lname"];
				$res["comandante"][$i] = $row["comandante"];
				$res["contracts"][$i] = $row["contracts"];
				$res["nationality"][$i] = $row["nationality"];
				$res["crafts"][$i] = $row["crafts"];
				$i++;
			}

			$cur = date_create(date("Y-m-d"));
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

				$valHelper = new Validity();
				$statuses = $valHelper->getPilotValidityStatuses($id);
				$res["allValid"][$i] = $statuses["all_valid"];
				$res["monthValid"][$i] = $statuses["validity_within_month"];
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