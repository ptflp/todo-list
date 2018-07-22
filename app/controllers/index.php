<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
} else {
	$user=new User();
	$user->authorize(1);
}
dump_r($_REQUEST['path']);
dump_r($_SESSION);
dump_r($user);