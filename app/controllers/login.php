<?php
require "../entities/User.php";
require "../entities/Todolist.php";
if (isset($_GET['email']) && isset($_GET['password'])) {
	$user = $entity_manager->getRepository('entities\User')->findOneBy(['email' => $_GET['email']]);
	echo $user->getEmail();
	if(password_verify($_GET['password'], $user->getPassword())) {
	    echo '<br>password true';
	}
}
?>