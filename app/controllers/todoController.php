<?php
use controllers\AppController;
use res\Controller;
use models\Todo;
/**
 * Todo controller for showing todolist by id
 */
class TodoController extends AppController
{
	public function actionIndex($id=false)
	{
		$this->checkParam(['id' => $id]);
		$todo = new Todo();
		$uid = $this->user->id;
		if ($todo->getUserTodoBy(['id' => $id, 'uid' => $uid])) {
			$this->view->layout='todo';
			echo $this->view->muRender('todo/index',[]);
		}
	}
}