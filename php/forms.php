<?php
	include_once "login.php";

	

	if(isset($_POST['p_create'])){
		$db = connect_db();

		//PERSONAL INFO ===================================
		$fname = strtolower($_POST['fname_i']);
		$lname = strtolower($_POST['lname_i']);
		$email = $_POST['email_i'];
		$nationality = $_POST['nationality_i'];
		$position = $_POST['pos_i'];
		$ang_license = $_POST['ang_lic_i'];
		$for_license = $_POST['for_lic_i'];
		if($_POST['hours_i'] != ""){
			$hours = $_POST['hours_i'];
		}else{
			$hours = 0;
		}
		

		$name = $fname." ".$lname;
		$pass = $_POST['p_create'];

		//ON-OFF DATES ARRAY ==============================
		$ondates = $_POST['date_on'];
		$offdates = $_POST['date_off'];

		//TEST EXPIRE DATES ===============================
		$ang_license_valid = $_POST['ang_lic_exp_i'];
		$for_license_valid = $_POST['for_lic_exp_i'];
		$passport = $_POST['passport_exp_i'];
		$ang_visa = $_POST['ang_visa_exp_i'];
		$us_visa = $_POST['us_visa_exp_i'];
		$instruments = $_POST['inst_exp_i'];
		$medical = $_POST['med_exp_i'];
		$booklet = $_POST['booklet_exp_i'];
		$sim = $_POST['sim_exp_i'];
		$training = $_POST['trainrec_exp_i'];
		$flight = $_POST['flight_train_exp_i'];
		$base = $_POST['base_exp_i'];
		$nightcur = $_POST['nightcur_exp_i'];
		$nightcheck = $_POST['night_exp_i'];
		$ifrcur = $_POST['ifrcur_exp_i'];
		$ifrcheck = $_POST['ifr_exp_i'];
		$line = $_POST['line_exp_i'];
		$hoistcheck = $_POST['hoistch_exp_i'];
		$hoistcur = $_POST['hoistcur_exp_i'];
		$crm = $_POST['crm_exp_i'];
		$hook = $_POST['hook_exp_i'];
		$herds = $_POST['herds_exp_i'];
		$dan_goods = $_POST['dang_exp_i'];
		$huet = $_POST['huet_exp_i'];
		$english = $_POST['english_exp_i'];
		$faids = $_POST['faids_exp_i'];
		$fire = $_POST['fire_exp_i'];

		//PILOT INFORMATION INSERTION ==============================
		$query = "INSERT INTO pilot_info VALUES ('".$fname."', '".$lname."', ".$position.", '".$nationality."' , '".$ang_license."', '".$for_license."' , '".$email."', ".$hours.")";
		if($insert = mysqli_query($db, $query)){
			$_SESSION['error'] = "";

			$query = "INSERT INTO available VALUES ('".$name."', ".$on.", ".$off.")";
			$success = true;

			for($i = 0; $i < count($ondates); $i++){
				if($ondates[$i] != ""){
					if($insert = mysqli_query($db, $query)){
						$_SESSION['error'] = "";
					}else{
						$success = false;
					}
				}
			}
			if($success){
				$query = "INSERT INTO tests VALUES ('".$name."', '".$for_license_valid."', 6, '".$ang_license_valid."', 24, '".$passport."', 60, '".$ang_visa."', 12, '".$us_visa."', 12, '".$instruments."', 12, '".$medical."', 6, '".$booklet."', 12, '".$sim."', 12, '".$training."', 12, '".$flight."', 12, '".$base."', 6, '".$nightcur."', 3, '".$nightcheck."', 6, '".$ifrcur."', 3, '".$ifrcheck."', 6, '".$line."', 12, '".$crm."', 12, '".$hoistcheck."', 12, '".$hoistcur."', 3, '".$hook."', 6, '".$herds."', 12, '".$dan_goods."', 24, '".$huet."', 36, '".$english.", 72, '".$faids."', 60, '".$fire."')";

				if($insert = mysqli_query($db, $query)){
					$_SESSION['error'] = "";

					$query = "INSERT INTO login VALUES ('".$name."', '".$pass."')";
					if($insert = mysqli_query($db, $query)){
						$_SESSION['error'] = "";
					}else{
						$_SESSION['error'] = "Failed to add pilot";
					}
				}else{
					$_SESSION['error'] = "Failed to add pilot";
				}
			}else{
				$_SESSION['error'] = "Failed to add pilot";
			}
		}else{
			$_SESSION['error'] = "Failed to add pilot";
		}

	}

//==========================================================================
//======================== ENDS CREATE NEW PILOT ===========================
//==========================================================================




//==========================================================================
//========================= ENDS SCHEDULE MAKER =============================
//==========================================================================



//==========================================================================
//=========================== ENDS RENEW TESTS =============================
//==========================================================================



//==========================================================================
//========================= ENDS PERSONAL INFO =============================
//==========================================================================
	if(isset($_POST['op'])){
		$db = connect_db();

		$oldpass = $_POST['op'];
		$user = strtolower($_SESSION['user']);

		$query = "SELECT password FROM login WHERE name='".$user."'";
		$result = mysqli_query($db, $query);
		$resultArray = mysqli_fetch_array($result);
		
		if($resultArray['password'] == $oldpass){
			$query = "UPDATE login SET password='".$_POST['np']."' WHERE name='".$user."'";
			if($update = mysqli_query($db, $query)){
				$_SESSION['error'] = "";
			}else{
				$_SESSION['error'] = "Failed to update password";
			}
		}
	}


//==========================================================================
//========================= ENDS CHANGE PASSWORD ===========================
//==========================================================================	


?>