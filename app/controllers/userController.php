<?php
use controllers\AppController;
use entities\User;
use models\User as AppUser;
/**
 * Controller
 */
class UserController extends AppController
{
	public $user;
	public function actionLogin($id=false)
	{
		$this->view->layout='logreg';
		echo $this->view->muRender('logreg/login',[]);
	}
	public function actionLogout()
	{
		$this->user->logout();
		$this->redirect('/user/login');
	}
	public function actionRegister()
	{
		if ($this->user->isAuthorized()) {
			header('location: /');
		}
		if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
			if($this->user->isExist($_REQUEST['email'])) {
				$this->msg(0,'данный пользователь уже существует: '.$_REQUEST['email']);
			} else {
				$this->user->register($_REQUEST['email'],$_REQUEST['password']);
				$this->user->authorize();
				$this->msg(1);
			}
		} else {
			$this->view->layout='logreg';
			echo $this->view->muRender('logreg/register',[]);
		}
	}
}