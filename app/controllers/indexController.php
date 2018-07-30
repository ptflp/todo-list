<?php
use res\Controller;
/**
 * Controller
 */
class IndexController extends Controller
{
	public function actionIndex()
	{
		$todo=new Todo;
		$todoList=$todo->getUserTodo($TodoApp->user->db);
		$sharedList=$todo->getShared($TodoApp->user->db,$TodoApp->db);
		$i=2;
		foreach ($sharedList as $key=>$value){
			$b=0;
			if ($i>12) {
				$i=2;
			}
			if ($i>9){$b='';}
			$sharedList[$key]['count']=$b.$i++;
		}
		$i=2;
		foreach ($todoList as $key=>$value){
			$b=0;
			if ($i>12) {
				$i=2;
			}
			if ($i>9){$b='';}
			$todoList[$key]['count']=$b.$i++;
		}
		$index['todoList']=$todoList;
		$index['sharedList']=$sharedList;
		$index['email']=$TodoApp->user->email;
		$view = $TodoApp->mustache->loadTemplate('index');
		echo $view->render($index);
	}
}