<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
} else {
	$main_user=new User();
}

switch (REQURL[1]) {
	case 'save':
	break;
	case 'create':
		if (isset($_REQUEST['title'])) {
			try {
				$user = $entity_manager->getRepository('entities\User')->findOneBy(['id' => $main_user->id]);
				$entity_manager->persist($user);
				// Create a new task
				$task = (new entities\Todolist())
				    ->setTitle($_REQUEST['title'])
				    ->setTasks("[{}]")
				    ->setUser($user)
				    ->setDate(new DateTime());

				// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
				// don't need to persist the task separately: it will be persisted when persisting
				// the User
				$user->addTodolist($task);

				// Finally flush and execute the database transaction
				$entity_manager->flush();
				$msg['success']=1;
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			} catch (Doctrine\DBAL\DBALException $e) {
				$msg['success']=0;
				$msg['error']=$e;
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			}
		} else {
			header('location: /');
		}
	break;
	case 'getlist':
	break;
	default:
		if (is_numeric($_REQUEST['id'])) {
			try {
				$todo = $entity_manager->getRepository('entities\Todolist')->findOneBy(['id' => $_REQUEST['id']]);
				if($todo){
					$user=$todo->getUser();
					if($user->getId() == $main_user->id) {
						require('../view/todo.html');
					} else {
						header('location: /');
					}
				} else {
					header('location: /');
				}
			} catch (Doctrine\DBAL\DBALException $e) {
				die('something went wrong');
			}
			//
		} else {
			header('location: /');
		}
	break;
}
