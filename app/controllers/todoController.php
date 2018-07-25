<?php
if (!$TodoApp->user->isAuthorized()) {
	header('location: /user/login');
}
switch (REQURL[1]) {
	default:
		if (is_numeric(REQURL[1])) {
			try {
				$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' => REQURL[1]]);
				if($todo){
					$user=$todo->getUser();
					if($user->getId() == $TodoApp->user->id) {
						require('../view/todo.php');
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
