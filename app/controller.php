<?php
$REQ = explode('/', $_REQUEST['path']);
define(REQURL,$REQ);
if(!empty($REQ[0])) {
	if (file_exists('../controllers/'.$REQ[0].'.php')) {
		include('../controllers/'.$REQ[0].'.php');
	} else {
		include('../controllers/404.php');
	}
} else {
	require('controllers/index.php');
}
?>