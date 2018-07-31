<?php
use controllers\AppController;
use models\Todo;
/**
 * Controller
 */
class IndexController extends AppController
{
	public function actionIndex()
	{
		$todo=new Todo;
		$todoList=$todo->getUserTodo($this->user->id);
		$sharedList=$todo->getShared($this->user->id);

		$sharedList=$this->addCountItem($sharedList);
		$todoList=$this->addCountItem($todoList);

		$content['todoList']=$todoList;
		$content['sharedList']=$sharedList;
		$content['email']=$this->user->email;
		echo $this->view->muRender('index',$content);
	}
	public function addCountItem($array)
	{
		$i=2;
		foreach ($array as $key=>$value){
			if ($i>12) {
				$i=2;
			}
			$array[$key]['count']=$i++;
		}
		return $array;
	}
}