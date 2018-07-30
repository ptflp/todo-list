<?php
use res\Controller;
/**
 * Controller
 */
class TodoController extends Controller
{
	public function actionIndex($id=false)
	{
		if (!$TodoApp->user->isAuthorized()) {
			header('location: /user/login');
		}
		if (is_numeric(REQURL[1])) {
			try {
				$todo=new Todo();
				$email=$TodoApp->user->email;
				$perm=$todo->checkPermByEmail(REQURL[1],$email,$TodoApp->db); // Check perm for writing
				if ($perm) {
					$todo = $TodoApp->db->getRepository('entities\Todolist')->findOneBy(['id' => REQURL[1]]);
					if($todo){
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
	}
}