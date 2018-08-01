<?php
namespace controllers;
use res\Controller;
use models\User;
/**
 * Controller
 */
class AppController extends Controller
{
	public $user;
	public $allow;
	function __construct()
	{
        parent::__construct();
		session_start();
        $this->user = new User;
        $this->allow=[ // Allow for not registered or authorized users
        				'api/register',
        				'api/login',
        				'user/login',
        				'user/register'
        			];
        $this->checkRoute();
	}
	/*
	* Show 404, reserved method for inherited controllers
	 */
	public function notFound()
	{
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$content['message'] = "404 Not Found";
		$this->view->layout='404';
 		echo $this->view->muRender('404',$content);
	}
	/*
	* Show api call status message
	 */
	public function msg($success,$error=false,$data=false)
	{
		$msg['success']=$success;
		$msg['error']=$error;
		$msg['data']=$data;
		echo json_encode($msg,JSON_UNESCAPED_UNICODE);
	}
	/*
	* Redirecting by url
	 */
	public function redirect($url)
	{
		header('location: '.$url);
	}
	/*
	* Checks required api params
	 */
	public function checkParam($arr)
	{
		if (isset($arr['id'])) {
			if (!is_numeric($arr['id'])) {
				$this->notFound();
				exit();
			}
		}
		if (isset($arr['request'])) {
			foreach ($arr['request'] as $key => $value) {
				if (!isset($_REQUEST[$value])) {
					$this->notFound();
					exit();
				}
			}
		}
	}
	/*
	* Checks routes allowed for not authorized users
	 */
	public function checkRoute()
	{
		$allow=0;
		foreach ($this->allow as $key => $value) {
			if($this->route == $value){
				$allow=1;
			}
		}
        if (!$this->user->isAuthorized() && !$allow) {
        	$this->redirect('/user/login');
        	exit();
        }
        if ($this->user->isAuthorized() && $allow) {
        	$this->redirect('/');
        	exit();
        }
	}

}