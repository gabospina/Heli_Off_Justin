<?php

include_once 'db_connect.php';
include_once 'validity.php';

$valHelper = new Validity();
$validities = $valHelper->getAllFields();
if($validities !== false) {
	print(json_encode($validities));
}else{
	print(false);
}