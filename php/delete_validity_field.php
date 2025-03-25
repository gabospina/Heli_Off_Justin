<?php
include_once 'db_connect.php';
include_once 'validity.php';

$valHelper = new Validity();

$key = $_POST['key'];
if($valHelper->deleteField($key)) {
	print('success');
}else {
	print('error: '.$valHelper->getError());
}