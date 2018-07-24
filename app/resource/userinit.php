<?php
$TodoApp->user= new User();
if (!empty($_SESSION['uid'])) {
	$TodoApp->user->authorize($_SESSION['uid'],$TodoApp->db);
}