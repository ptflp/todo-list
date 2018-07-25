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
	case 'remove':
		if (is_numeric(REQURL[2])) {
			try {
				$user=$TodoApp->user->db;
				$TodoApp->db->persist($user);
				// Create a new task
				$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[2]]);
				if (is_object($todo)) {
					$Tuser=$todo->getUser();
					if($Tuser->getId() == $TodoApp->user->id) {
						// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
						// don't need to persist the task separately: it will be persisted when persisting
						// the User
						$user->removeTodolist($todo);
						// Finally flush and execute the database transaction
						$TodoApp->db->flush();
						$msg['success']=1;
						echo json_encode($msg,JSON_UNESCAPED_UNICODE);
					} else {
						$msg['success']=0;
						$msg['error']='nope';
						echo json_encode($msg,JSON_UNESCAPED_UNICODE);
					}
				} else {
					$msg['success']=0;
					$msg['error']='nope';
					echo json_encode($msg,JSON_UNESCAPED_UNICODE);
				}
			} catch (Doctrine\DBAL\DBALException $e) {
				$msg['success']=0;
				$msg['error']=$e;
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			}
		} else {
			header('location: /');
		}
	break;
	case 'save':
		if (is_numeric(REQURL[2])) {
			$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[2]]);
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
							if($value->title && mb_strlen($value->id)== 17 && is_bool($value->complete)){
								$arr[]=$item;
							}
						}
						$json=json_encode($arr);
						$todo->setTasks($json);
						$TodoApp->db->merge($todo);
						$TodoApp->db->flush();
						echo $json;
					} else {
						header('location: /');
					}
				} else {
					$msg['success']=0;
					$msg['error']='nope';
					echo json_encode($msg,JSON_UNESCAPED_UNICODE);
				}
			} else {
				header('location: /');
			}
		} else {
			header('location: /');
		}
	break;
	case 'get':
		if (is_numeric(REQURL[2])) {
			try {
				$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[2]]);
				if(is_object($todo)){
					$msg['success']=1;
					$msg['title']=$todo->getTitle();
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
	default:
		if($_REQUEST['settings']) {
			$url['create']="/api/create/";
			$url['get']="/api/get/";
			$url['save']="/api/save/";
			$url['remove']="/api/remove/";
			echo json_encode($url,JSON_UNESCAPED_UNICODE);
		} else {
			header('location: /404');
		}
	break;
}
