<?php
use res\Controller;
use entities\User;
use models\User as AppUser;
/**
 * Controller
 */
class UserController extends Controller
{
	public $user;
	function __construct()
	{
        parent::__construct();
		session_start();
        $this->user = new AppUser;
	}
	public function actionLogin($id=false)
	{
		if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
			$user = $this->user;
			$db = $this->user->db;
			$auth=$user->auth($_REQUEST['email'],$_REQUEST['password'],$db);
			if ($auth) {
				$user->authorize($user->id,$db);
				$this->msg(1);
			} else {
				$this->msg(0,'Неправильная пара логин пароль');
			}
		} else {
			if ($this->user->isAuthorized()) {
				header('location: /');
			}
			$this->view->layout='logreg';
			echo $this->view->muRender('logreg/login',[]);
		}
	}
	public function actionLogout()
	{
		$this->user->logout();
		header('location: /user/login');
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
	public function msg($success,$error=false,$data=false)
	{
		$msg['success']=$success;
		$msg['error']=$error;
		$msg['data']=$data;
		echo json_encode($msg,JSON_UNESCAPED_UNICODE);
	}
}