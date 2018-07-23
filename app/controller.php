<?php
$REQ = explode('/', $_REQUEST['path']);
define(REQURL,$REQ);
if(!empty($REQ[0])) {
	if (file_exists('../controllers/'.$REQ[0].'Controller.php')) {
		include('../controllers/'.$REQ[0].'Controller.php');
	} else {
		include('../controllers/404.php');
	}
} else {
	require('controllers/indexController.php');
}
?>