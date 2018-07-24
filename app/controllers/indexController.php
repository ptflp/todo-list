<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
}
include('../view/index.php');