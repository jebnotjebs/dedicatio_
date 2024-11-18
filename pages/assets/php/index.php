<?php
include('config/database.php');
$formula = '';

if (isset($_POST['formula'])) {
	switch ($_POST['formula']) {

		case 'birthday_':
			$query = "SELECT * FROM `dedications` WHERE category_code = ?;";
			$params = ['BIRTHDAY'];
			$supData = $database->prepareAndExecute($query, $params);
			echo json_encode($supData);
		break;

		default:
			echo "1";
		break;
	}
}else{
	echo "cant";
}


