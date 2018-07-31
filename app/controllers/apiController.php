<?php
use controllers\AppController;
use res\Controller;
use models\Todo;
/**
 * Controller
 */
class ApiController extends AppController
{
	public function actionCreate()
	{
		if (isset($_REQUEST['title'])) {
			$todo = new Todo();
			if ($todo->createTodo($this->user->id,$_REQUEST['title'])) {
				$this->msg(1);
			}
		} else {
			$this->notFound();
		}
	}
	public function actionRemove($id)
	{
		if (is_numeric($id)) {
			try {
				$todo=new Todo();
				$uid=$this->user->id;
				if ($todo->todoRemove($id,$uid)) {
					$this->msg(1);
				} else {
					$this->msg(0);
				}
			} catch (Doctrine\DBAL\DBALException $e) {
				$this->msg(0,$e);
			}
		} else {
			$this->notFound();
		}
	}
	public function actionSave()
	{
		if (is_numeric(REQURL[2])) {
			$todo=new Todo();
			$email=$TodoApp->user->email;
			$perm=$todo->checkPermByEmail(REQURL[2],$email,$TodoApp->db); // Check perm for writing
			if ($perm==1 || $perm==2) {
				$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' =>REQURL[2]]);
				if($todo){
					$user=$todo->getUser();
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
						$this->notFound();
					}
				} else {
					$this->notFound();
				}
			}
		} else {
			$this->notFound();
		}
	}
	public function actionGet()
	{
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
			$this->notFound();
		}
	}
	public function actionEdit()
	{
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
			$this->notFound();
		}
	}
	public function actionTest()
	{
		$m = new Mustache_Engine;
		echo $m->render('Hello {{planet}}', array('planet' => 'World!'));
	}
	public function actionShare()
	{
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
	}
	public function actionSettings()
	{
		if(isset($_REQUEST['settings'])) {
			$url['create']="/api/create/";
			$url['get']="/api/get/";
			$url['edit']="/api/edit/";
			$url['save']="/api/save/";
			$url['remove']="/api/remove/";
			$url['share']="/api/share/";
			echo json_encode($url,JSON_UNESCAPED_UNICODE);
		} else {
			$this->notFound();
		}
	}
}