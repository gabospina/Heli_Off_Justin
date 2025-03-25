<?php
	date_default_timezone_set('UTC'); 
	//upload file
	if (!empty($_FILES['file'])){
		include_once "db_connect.php";
		$type = strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], ".")+1));	// uncaps the extension

		$size = $_FILES['file']['size'];	// in bytes
		$timestamp = time();
		$fname = str_replace(" ", "_", str_ireplace(".".$type, "-".$timestamp.".".$type, $_FILES['file']['name']));	// add timestamp
		$name = substr($fname, 0, strrpos($fname, "."));

		if(move_uploaded_file($_FILES['file']['tmp_name'], "../../uploads/pictures/".$fname)){
			if(isset($_REQUEST["pilot_id"]))
				$user = $_REQUEST["pilot_id"];
			else
				$user = $_SESSION["HeliUser"];
			
			$query = "SELECT profile_picture FROM pilot_info WHERE id=$user";
			$result = $mysqli->query($query);
			if($result != false){
				$oldFile = $result->fetch_assoc()["profile_picture"];
				if($oldFile != null){
					unlink("../../uploads/pictures/".$oldFile);
				}
				$update = "UPDATE pilot_info SET profile_picture='$fname' WHERE id=$user";
				if($mysqli->query($update)){
					print("success=".$fname);
				}else{
					print("failed: ".$mysqli->error);
				}
			}else{
				print("failed: ".$mysqli->error);
			}
		}else{
			print_r($_FILES["file"], true);
		}	
	}else{
		print(json_encode($_FILES));
	}

?>