<?php
use controllers\AppController;
use res\Controller;
use models\Todo;
/**
 * Controller
 */
class TodoController extends AppController
{
	public function actionIndex($id=false)
	{
		if (!$this->user->isAuthorized()) {
			header('location: /user/login');
		}
		if (is_numeric($id)) {
			try {
				$todo=new Todo();
				$email=$this->user->email;
				$perm=$todo->checkPermByEmail($id,$email); // Check perm for writing
				if ($perm) {
					$db=Todo::getDoctrine();
					$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' => $id]);
					if($todo){
						$this->view->layout='todo';
						echo $this->view->muRender('todo/index',[]);
					} else {
						$this->notFound();
					}
				} else {
					$this->notFound();
				}
			} catch (Doctrine\DBAL\DBALException $e) {
				die('something went wrong');
			}
		} else {
			$this->notFound();
		}
	}
}