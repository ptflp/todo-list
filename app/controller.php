<?php
$req = explode('/', $_REQUEST['path']);
if(!empty($req[0])) {
	if (file_exists('../controllers/'.$req[0].'.php')) {
		include('../controllers/'.$req[0].'.php');
	} else {
		include('../controllers/404.php');
	}
} else {
	require('controllers/index.php');
}
?>