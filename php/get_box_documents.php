<?php
	$ch = curl_init("https://view-api.box.com/1/documents");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Token ayalopqwqvogv63yfhtbx5tfzy1vxigx", "Content-Type: application/json"));

	$res = curl_exec($ch);
	
	if($res !== false){
		if(gettype($res) != "array" || gettype($res) != "object"){
			$res = json_decode($res, true);
		}
		print(json_encode($res));
	}else{
		$err = curl_error($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		print(json_encode(array("error"=>$err, "status"=>$status, "res"=>$res)));
	}

?>