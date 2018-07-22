<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
} else {
	$main_user=new User();
}
include('../view/index.html');