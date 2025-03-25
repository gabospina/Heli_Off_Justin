<?php
	include_once "db_connect.php";

	$user = $_SESSION["HeliUser"];
	//select threads that this user is in with timestamp and read status
	$query = "SELECT DISTINCT t.thread_id, t.last_updated, tu.user_id, tu.read FROM threads t INNER JOIN thread_users tu ON tu.thread_id=t.thread_id WHERE tu.user_id=$user ORDER BY t.last_updated DESC";
	$result = $mysqli->query($query);
	$res = array();
	$read = true;
	if($result != false){
		while($row = $result->fetch_assoc()){
			//get all of the names associated with that thread
			$msgQuery = "SELECT CONCAT(p.fname,' ',p.lname) AS name FROM pilot_info p INNER JOIN thread_users tu ON tu.user_id=p.id WHERE tu.thread_id=$row[thread_id] AND tu.user_id!=$user";
			$nameStr = "";
			$searchStr = "";
			$i = 0;
			$extra = 0;
			$msgResult = $mysqli->query($msgQuery);
			if($msgResult != false){
				while($msgRow = $msgResult->fetch_assoc()){
					if($i < 6){
						$nameStr .= ($i != 0 ? ", ":"").$msgRow["name"];
					}else{
						$extra++;
					}
					$searchStr .= ($i != 0 ? ", ":"").$msgRow["name"];
					$i++;
				}
				if($extra != 0){
					$nameStr .= " +".$extra." more";
				}
			}
			if($row["read"] == 0){
				$read = false;
			}
			array_push($res, array("pk"=>$row["thread_id"], "name"=>$nameStr, "search"=>$searchStr, "last_updated"=>$row["last_updated"], "read"=>$row["read"]));
		}
	}
	print(json_encode(array("result"=>$res, "read"=>$read)));
?>