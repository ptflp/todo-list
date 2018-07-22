<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
} else {
	$user=new User();
}

switch (REQURL[1]) {
	case 'save':
	break;
	case 'create':
		if (isset($_REQUEST['title'])) {
			$user = $entity_manager->getRepository('entities\User')->findOneBy(['id' => $user->id]);
			$entity_manager->persist($user);
			// Create a new task
			$task = (new entities\Todolist())
			    ->setTitle($_REQUEST['title']);
			    ->setTasks("[{}]");
			    ->setUser($user)
			    ->setDate(new DateTime());

			// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
			// don't need to persist the task separately: it will be persisted when persisting
			// the User
			$user->addTodolist($task);

			// Finally flush and execute the database transaction
			$entity_manager->flush();
		} else {
			header('location: /');
		}
	break;
	case 'getlist':
	break;
	default:
		header('location: /');
	break;
}
