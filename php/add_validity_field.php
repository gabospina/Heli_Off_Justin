<?php
include_once 'validity.php';
$valHelper = new Validity();

if($valHelper->addNewField($_POST['field_key'], $_POST['field_name'], $_POST['validity_period'])) {
	print('success');
}else {
	print($valHelper->getError());
}