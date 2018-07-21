<?php
require "../entities/User.php";
require "../entities/Todolist.php";
if (isset($_GET['email']) && isset($_GET['password'])) {
	$options = [
	    'cost' => 10
	];
	$hash=password_hash($_GET['password'], PASSWORD_DEFAULT,$options);
	$user = (new entities\User())
	    ->setEmail($_GET['email'])
	    ->setPassword($hash);
	// $user = $entity_manager->getRepository('entities\User')->findOneBy(['id' => 2]);
	$entity_manager->persist($user);
	// Finally flush and execute the database transaction
	$entity_manager->flush();
}