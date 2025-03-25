<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	
	$start = $_POST['start'];
	$end = $_POST['end'];
	$account = $_SESSION["account"];

	$details = false;
	if(isset($_POST["details"])){
		$details = true;
	}
	if(isset($_POST["contract"])){
		if($_POST["contract"] == "any"){
			$cond1 = "";
		}else{
			$contract = $_POST["contract"];
			$cond1 = "AND craft IN (SELECT cr.id FROM crafts cr, contracts con INNER JOIN contract_info coi ON coi.id=con.contract_id WHERE coi.name='$contract' AND cr.id=con.craftid)";

		}
	}else{
		$cond1 = "";
	}

	if(isset($_POST["craft"]) && $_POST["craft"] != "all"){
		$craft = $_POST["craft"];
		$cond2 = "AND craft IN (SELECT id FROM crafts WHERE craft='$craft')";
	}else{
		$cond2 = "";
	}
	$sql = "SELECT id,
				   sched_date, 
				   craft, 
				   pos FROM schedule WHERE sched_date BETWEEN '$start' AND '$end' AND craft IN (SELECT id FROM crafts WHERE account=$account) $cond1 $cond2 ORDER BY sched_date, craft";
	$result = $mysqli->query($sql);
	if($result != FALSE){
		$res = array();
		$res["classes"] = array();
		$res["heliContracts"] = array();
		$i=0;
		while($row = mysqli_fetch_assoc($result)){
			$res['date'][$i] = $row['sched_date'];
			$res['craft'][$i] = $row['craft'];
			$res['pos'][$i] = $row['pos'];
			$id = $row["id"];
			$res['id'][$i] = $id;
			$sql2 = "SELECT pilot_info.fname , pilot_info.lname FROM pilot_info WHERE pilot_info.id=$id";
			$result2 = $mysqli->query($sql2);
			if($result2 != FALSE){
				$row2 = mysqli_fetch_assoc($result2);
				$res['name'][$i] = $row2['lname'].". ".strtoupper(substr($row2["fname"], 0, 1));
			}
			if($details){
				$sql3 = "SELECT id, value FROM schedule_details WHERE `date`='".$row['sched_date']."' AND craft= ".$row["craft"];
				$result3 = $mysqli->query($sql3);
				if($result3->num_rows >= 1){
					$row = $result3->fetch_assoc();
					$res["details"][$i] = $row["value"];
					$res["detailID"][$i] = $row["id"];
				}else{
					$res["details"][$i] = "&nbsp;";
					$res["detailID"][$i] = "dne";
				}
			}
			$i++;
		}

		if($details){
			$query = "SELECT * FROM scala_records WHERE `date`='$start' AND pilot_id IN (SELECT id FROM pilot_info WHERE account=$account)";
			$result = $mysqli->query($query);
			$res["records"] = array();
			if($result != false){
				while($row = $result->fetch_assoc()){
					$minDaily = round(($row['daily']-intval($row['daily']))*60);
					$row['daily'] = intval($row['daily']).":".($minDaily > 9 ? $minDaily : '0'.$minDaily);
					$minFlown = round(($row['flown']-intval($row['flown']))*60);
					$row['flown'] = intval($row['flown']).":".($minFlown > 9 ? $minFlown : '0'.$minFlown);
					$res["records"][$row["craft"]][$row["position"]] = $row;
				}
			}
		}

		if(isset($_POST["contract"]) || isset($_POST["craft"])){
			if(isset($_POST["contract"])){
				if($_POST["contract"] == "any"){
					$cond1 = "";
				}else{
					$contract = $_POST["contract"];
					$cond1 = "coi.name='$contract' AND cr.id=con.craftid";
				}
			}else{
				$cond1 = "";
			}

			if(isset($_POST["craft"]) && $_POST["craft"] != "all"){
				$craft = $_POST["craft"];
				$cond2 = "cr.craft='$craft'";
			}else{
				$cond2 = "";
			}

			if($cond1 != "" && $cond2 != ""){
				$where = " WHERE $cond1 AND $cond2 AND";
			}elseif($cond2 != ""){
				$where = " WHERE $cond2 AND";
			}elseif($cond1 != ""){
				$where = " WHERE $cond1 AND";
			}else{
				$where = " WHERE ";
			}
			$sql3 = "SELECT DISTINCT cr.id AS id, cr.class AS class, cr.tod AS TOD, coi.name AS contract, cr.alive AS alive FROM crafts cr INNER JOIN contracts con ON con.craftid=cr.id INNER JOIN contract_info coi ON coi.id=con.contract_id $where cr.account=$account AND coi.account=$account ORDER BY coi.order, cr.class";
			$result3 = $mysqli->query($sql3);
			if($result3 != FALSE){
				while($row3 = mysqli_fetch_assoc($result3)){
					array_push($res["classes"], array("id"=>$row3['id'], "class"=>$row3["class"], "tod"=>$row3["TOD"], "alive"=>intval($row3["alive"])));
					array_push($res["heliContracts"], $row3["contract"]);
				}
			}
		}
			
		print_r(json_encode($res));
	}else{
		print_r(false);
	}

	mysqli_close($mysqli);
?>