<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
} else {
	$user=new User();
}
include('../view/index.php');