<?php
session_start();
function sse($msg){
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: $msg".PHP_EOL;
	echo PHP_EOL;
	ob_flush();
	flush();
}

if(isset($_SESSION["employee_id"])){
	
	foreach ($_SESSION as $key => $value) {
		$_SESSION[$key] = $value;
	}
	sse("exist");
}
else
{
	sse("expired");
}

?>