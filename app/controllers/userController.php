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
		$this->view->layout='logreg';
		echo $this->view->muRender('logreg/register',[]);
	}
}