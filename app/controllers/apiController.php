<?php
use controllers\AppController;
use res\Controller;
use models\Todo;
/**
 * Controller
 */
class ApiController extends AppController
{
	public $todo;
	function __construct()
	{
		parent::__construct();
		$this->todo = new Todo();
	}
	public function actionCreate()
	{
		if (isset($_REQUEST['title'])) {
			$todo = $this->todo;
			$uid = $this->user->id;
			if ($todo->createTodo($uid,$_REQUEST['title'])) {
				$this->msg(1);
			}
		} else {
			$this->notFound();
		}
	}
	public function actionRemove($id=false)
	{
		if (is_numeric($id)) {
			$todo = $this->todo;
			$uid=$this->user->id;
			if ($todo->todoRemove($id,$uid)) {
				$this->msg(1);
			} else {
				$this->msg(0);
			}
		} else {
			$this->notFound();
		}
	}
	public function actionSave($id=false)
	{
		if (is_numeric($id)) {
			$todo = $this->todo;
			$data = $_REQUEST['data'];
			$uid = $this->user->id;
			if ($todo->saveUserTasks($id,$data,$uid)) {
				echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
			} else {
				$this->msg(0);
			}
		} else {
			$this->notFound();
		}
	}
	public function actionGet($id=false)
	{
		if (is_numeric($id)) {
			$todo = $this->todo;
			$uid = $this->user->id;
			if ($todo->getUserTasks($id,$uid)) {
				echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
			} else {
				$this->msg(0);
			}
		} else {
			$this->notFound();
		}
	}
	public function actionEdit($id=false)
	{
		if (is_numeric($id)) {
			try {
				$todo = $this->todo;
				$email=$this->user->email;
				$perm=$todo->checkPermByEmail($id,$email); // Check perm for writing
				if ($perm==1) {
					$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' =>$id]);
					$user=$todo->getUser();
					if(is_object($todo) && $user->getId() == $TodoApp->user->id){
						$todo->setTitle($_REQUEST['title']);
						$db->merge($todo);
						$db->flush();
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
		if (is_numeric($id)) {
	 		if (filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL) && is_numeric($_REQUEST['permission'])) {
	 			$permission=[2,3];
	 			if (!in_array($_REQUEST['permission'],$permission)) {
					header('location: /');
	 			}
				try {
					$todo = $this->todo;
					$email=$this->user->email;
					$perm=$todo->checkPermByEmail($id,$email); // Check perm for writing
					if ($perm==1) {
						if($email==$_REQUEST['email']) {
							msgError();
						} else {
							$todo->setPermission($id,$_REQUEST['email'],$_REQUEST['permission']);
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