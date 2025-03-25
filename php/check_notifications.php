<?php
	include_once "db_connect.php";

	$page = $_GET["page"];
	$user = $_SESSION["HeliUser"];
	$res = array();
	if(isset($_GET["selected"])){
		$mysqli->query("UPDATE thread_users SET `read`=1 WHERE user_id=$user AND thread_id=$_GET[selected]");
	}
	// if($page != "messaging"){
		$query = "SELECT thread_id FROM thread_users WHERE `read`=0 AND user_id=$user";
		$result = $mysqli->query($query);
		if($result != false){
			while($row = $result->fetch_assoc()){
				$msgQuery = "SELECT CONCAT(p.fname,' ',p.lname) AS name FROM pilot_info p INNER JOIN thread_users tu ON tu.user_id=p.id WHERE tu.thread_id=$row[thread_id] AND tu.user_id!=$user";
				$nameStr = "";
				$i = 0;
				$count = 0;
				$msgResult = $mysqli->query($msgQuery);
				if($msgResult != false){
					while($msgRow = $msgResult->fetch_assoc()){
						if($i < 3){
							$nameStr .= ($i != 0 ? ", ":"").$msgRow["name"];
						}else{
							$count++;
						}							
						$i++;
					}
				}
				if($count != 0){
					$nameStr .= " + ".$count." more";
				}
				array_push($res, array("type"=>"message", "pk"=>$row["thread_id"], "title"=>$nameStr));
			}
		}
	// }

		$query = "SELECT COUNT(id) AS num FROM news_notifications WHERE user_id=$user";
		$result = $mysqli->query($query);
		if($result != false){
			while($row = $result->fetch_assoc()){
				if($row["num"] != 0)
				array_push($res, array("type"=>"news", "title"=>"<span class='fa fa-newspaper-o'></span> ".$row["num"]." news update".($row["num"] > 1 ? "s":"")."."));
			}
		}
	print(json_encode($res));
?>