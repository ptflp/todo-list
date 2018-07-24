<?php
if (!$TodoApp->user->isAuthorized()) {
	header('location: /user/login');
}
switch (REQURL[1]) {
	case 'create':
		if (isset($_REQUEST['title'])) {
			try {
				// $user = $TodoApp->db->getRepository('entities\User')->findOneBy(['id' => $TodoApp->user->id]);
				// $TodoApp->db->persist($user);
				$user=$TodoApp->user->db;
				$TodoApp->db->persist($user);
				// Create a new task
				$task = (new entities\Todolist())
				    ->setTitle($_REQUEST['title'])
				    ->setTasks("[]")
				    ->setUser($user)
				    ->setDate(new DateTime());

				// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
				// don't need to persist the task separately: it will be persisted when persisting
				// the User
				$user->addTodolist($task);

				// Finally flush and execute the database transaction
				$TodoApp->db->flush();
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
	case 'action':
		switch (REQURL[2]) {
			case 'get':
				if (is_numeric(REQURL[3])) {
					try {
						$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[3]]);
						if(is_object($todo)){
							$msg['success']=1;
							$msg['data']=json_decode($todo->getTasks());
							echo json_encode($msg,JSON_UNESCAPED_UNICODE);
						} else {
							$msg['success']=0;
							$msg['error']='nope';
							echo json_encode($msg,JSON_UNESCAPED_UNICODE);
						}
					} catch (Doctrine\DBAL\DBALException $e) {
						die('something went wrong');
					}
				} else {
					header('location: /');
				}
			break;
			case 'save':
				if (is_numeric(REQURL[3])) {
					$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[3]]);
					if($todo){
						$user=$todo->getUser();
						if($user->getId() == $TodoApp->user->id) {
							$array=json_decode($_REQUEST['data']);
							if (is_array($array)) {
								$arr=[];
								$i=0;
								foreach ($array as $key => $value) {
									$item = new stdClass();
									$item->title=$value->title;
									$item->complete=$value->complete;
									$item->id=$value->id;
									$arr[]=$item;
								}
								$json=json_encode($arr);
								$todo->setTasks($json);
								$TodoApp->db->merge($todo);
								$TodoApp->db->flush();
								echo $json;
							} else {
								header('location: /');
							}
						}
					} else {
						header('location: /');
					}
				} else {
					header('location: /');
				}
			break;
			default:
				header('location: /');
			break;
		}
		// } else {
		// 	header('location: /');
		// }
	break;
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
