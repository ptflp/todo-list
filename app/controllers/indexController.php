<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
} else {
	$user=new User();
}
dump_r($_REQUEST['path']);
dump_r($_SESSION);
dump_r($user);