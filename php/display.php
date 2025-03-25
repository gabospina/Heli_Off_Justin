<?php
	include_once 'login.php';

		$today = getdate();
	$daysBack = 0;

	switch($today['wday']){
		case 0: $daysBack = -6; 
		break;
		case 6: $daysBack = -5; 
		break;
		case 5: $daysBack = -4; 
		break;
		case 4: $daysBack = -3; 
		break;
		case 3: $daysBack = -2; 
		break;
		case 2: $daysBack = -1;
		break;
		case 1: $daysBack = 0; 
		break;
	}

	if($daysBack == 0){
		$monday = date("d/m/Y", strtotime("now"));
	}elseif($daysBack == -1){
		$monday = date("d/m/Y", strtotime("+".$daysBack." day"));
	}else{
		$monday = date("d/m/Y", strtotime("+".$daysBack." days"));
	}

	$db = connect_db();

	$query = "SELECT * FROM schedule WHERE date='".$monday."'";
	$result = mysqli_query($db, $query);
	$resultArray = mysqli_fetch_array($result);

	$query = "SELECT * FROM schedule WHERE week_no=".$resultArray['week_no'];
	$result = mysqli_query($db, $query);

	$_SESSION['myMonday'] = false;
	$_SESSION['myTuesday'] = false;
	$_SESSION['myWednesday'] = false;
	$_SESSION['myThursday'] = false;
	$_SESSION['myFriday'] = false;
	$_SESSION['mySaturday'] = false;
	$_SESSION['mySunday'] = false;
	
	if($result != false){
		$dayNo = 0;
		$dayCount = 0;
		$dayson = array();
		$dayClass = array();
		$dayWith = array();
		$dayPos = array();
		while($row = mysqli_fetch_array($result)){
			for($i = 2; $i < 24; $i++){
				if($row[$i] == strtolower($_SESSION['user'])){
					$dayson[$dayCount] = $dayNo;

					if($i%2 == 0){
						$dayPos[$dayCount] = "comandante";
					}
					$dayClass = $i;
					if($dayPos[$dayCount] == "commandante"){
						$dayWith[$dayCount] = $row[$i+1];
					}else{
						$dayWith[$dayCount] = $row[$i-1];
					}
					$dayCount++;
				}
			}
			$dayNo++;
		}

		for($i = 0; $i < count($dayson); $i++){
			switch($dayson[$i]){
				case 0: $_SESSION['myMonday'] = true;
						$_SESSION['myMonClass'] = $dayClass[$i];
						$_SESSION['myMonPos'] = $dayPos[$i];
						$_SESSION['myMonWith'] = $dayWith[$i];
				break;
				case 1: $_SESSION['myTuesday'] = true; 
						$_SESSION['myTuesClass'] = $dayClass[$i];
						$_SESSION['myTuesPos'] = $dayPos[$i];
						$_SESSION['myTuesWith'] = $dayWith[$i];
				break;
				case 2: $_SESSION['myWednesday'] = true; 
						$_SESSION['myWedClass'] = $dayClass[$i];
						$_SESSION['myWedPos'] = $dayPos[$i];
						$_SESSION['myWedWith'] = $dayWith[$i];
				break;
				case 3: $_SESSION['myThursday'] = true; 
						$_SESSION['myThurClass'] = $dayClass[$i];
						$_SESSION['myThurPos'] = $dayPos[$i];
						$_SESSION['myThurWith'] = $dayWith[$i];
				break;
				case 4: $_SESSION['myFriday'] = true;
						$_SESSION['myFriClass'] = $dayClass[$i];
						$_SESSION['myFriPos'] = $dayPos[$i];
						$_SESSION['myFriWith'] = $dayWith[$i]; 
				break;
				case 5: $_SESSION['mySaturday'] = true; 
						$_SESSION['mySatClass'] = $dayClass[$i];
						$_SESSION['mySatPos'] = $dayPos[$i];
						$_SESSION['mySatWith'] = $dayWith[$i];
				break;
				case 6: $_SESSION['mySunday'] = true; 
						$_SESSION['mySunClass'] = $dayClass[$i];
						$_SESSION['mySunPos'] = $dayPos[$i];
						$_SESSION['mySunWith'] = $dayWith[$i];
				break;
			}
		}
	}
	




//==========================================================================
//====================== ENDS MYSCHEDULE DISPLAY ===========================
//==========================================================================

?>