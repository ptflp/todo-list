<?php
if (!$TodoApp->user->isAuthorized()) {
	header('location: /user/login');
}
function msgError()
{
	$msg['success']=0;
	$msg['error']='nope';
	echo json_encode($msg,JSON_UNESCAPED_UNICODE);
}
switch (REQURL[1]) {
	case 'create':
		if (isset($_REQUEST['title'])) {
			try {
				// $user = $TodoApp->db->getRepository('entities\User')->findOneBy(['id' => $TodoApp->user->id]);
				// $TodoApp->db->persist($user);
				$user=$TodoApp->user->db;
				$TodoApp->db->persist($user);
				// Create a new todolist
				$todo = (new entities\Todolist())
				    ->setTitle($_REQUEST['title'])
				    ->setTasks("[]")
				    ->setUser($user)
				    ->setDate(new DateTime());

				// Add the todolist the to list of the User tasks. Since we used cascade={"all"}, we
				// don't need to persist the todolist separately: it will be persisted when persisting
				// the User
				$user->addTodolist($todo);

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
				$todo=new Todo();
				$email=$TodoApp->user->email;
				$perm=$todo->checkPermByEmail(REQURL[2],$email,$TodoApp->db); // Check perm for writing
				if ($perm==1) {
					$user=$TodoApp->user->db;
					$TodoApp->db->persist($user);
					// Create a new task
					$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[2]]);
					if (is_object($todo)) {
						// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
						// don't need to persist the task separately: it will be persisted when persisting
						// the User
						$user->removeTodolist($todo);
						// Finally flush and execute the database transaction
						$TodoApp->db->flush();
						$msg['success']=1;
						echo json_encode($msg,JSON_UNESCAPED_UNICODE);
					} else {
						msgError();
					}
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
			$todo=new Todo();
			$email=$TodoApp->user->email;
			$perm=$todo->checkPermByEmail(REQURL[2],$email,$TodoApp->db); // Check perm for writing
			if ($perm==1 || $perm==2) {
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
						msgError();
					}
				} else {
					header('location: /');
				}
			}
		} else {
			header('location: /');
		}
	break;
	case 'get':
		if (is_numeric(REQURL[2])) {
			try {
				$todo=new Todo();
				$email=$TodoApp->user->email;
				$perm=$todo->checkPermByEmail(REQURL[2],$email,$TodoApp->db); // Check perm for writing
				if ($perm) {
					$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' => REQURL[2]]);
					if(is_object($todo)){
						$msg['success']=1;
						$msg['title']=$todo->getTitle();
						$msg['data']=json_decode($todo->getTasks());
						echo json_encode($msg,JSON_UNESCAPED_UNICODE);
					} else {
						msgError();
					}
				}
			} catch (Doctrine\DBAL\DBALException $e) {
				die('something went wrong');
			}
		} else {
			header('location: /');
		}
	break;
	case 'edit':
		if (is_numeric(REQURL[2])) {
			try {
				$todo=new Todo();
				$email=$TodoApp->user->email;
				$perm=$todo->checkPermByEmail(REQURL[2],$email,$TodoApp->db); // Check perm for writing
				if ($perm==1) {
					$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[2]]);
					$user=$todo->getUser();
					if(is_object($todo) && $user->getId() == $TodoApp->user->id){
						$todo->setTitle($_REQUEST['title']);
						$TodoApp->db->merge($todo);
						$TodoApp->db->flush();
						$msg['success']=1;
						$msg['title']=$todo->getTitle();
						$msg['data']=json_decode($todo->getTasks());
						echo json_encode($msg,JSON_UNESCAPED_UNICODE);
					} else {
					msgError();
					}
				} else {
					msgError();
				}

			} catch (Doctrine\DBAL\DBALException $e) {
				die('something went wrong'.$e);
			}
		} else {
			header('location: /');
		}
	break;
	case 'test':
		$todo=new Todo();
		$user=$TodoApp->db->getRepository('entities\User')->findOneBy(['email' => 'no@no.no']);
		$todo->getShared($user,$TodoApp->db);
	break;
	case 'share':
		if (is_numeric(REQURL[2])) {
	 		if (filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL) && is_numeric($_REQUEST['permission'])) {
	 			$permission=[2,3];
	 			if (!in_array($_REQUEST['permission'],$permission)) {
					header('location: /');
	 			}
				try {
					$todo=new Todo();
					$email=$TodoApp->user->email;
					$perm=$todo->checkPermByEmail(REQURL[2],$email,$TodoApp->db); // Check perm for writing
					if ($perm==1) {
						if($email==$_REQUEST['email']) {
							msgError();
						} else {
							$todo->setPermission(REQURL[2],$_REQUEST['email'],$_REQUEST['permission'],$TodoApp->db);
							echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
						}
					} else {
						$msg['success']=0;
						$msg['error']='nope';
						echo json_encode($msg,JSON_UNESCAPED_UNICODE);
					}
				} catch (Doctrine\DBAL\DBALException $e) {
					msgError();
				}
			}
		} else {
			msgError();
		}
	break;
	default:
		if($_REQUEST['settings']) {
			$url['create']="/api/create/";
			$url['get']="/api/get/";
			$url['edit']="/api/edit/";
			$url['save']="/api/save/";
			$url['remove']="/api/remove/";
			$url['share']="/api/share/";
			echo json_encode($url,JSON_UNESCAPED_UNICODE);
		} else {
			header('location: /404');
		}
	break;
}
