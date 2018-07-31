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
	function __construct()
	{
        parent::__construct();
		session_start();
        $this->user = new User;
        if (!$this->user->isAuthorized()) {
        	$this->redirect('/user/login');
        	exit();
        }
	}
	public function notFound()
	{
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$content['message'] = "404 Not Found";
		$this->view->layout='404';
 		echo $this->view->muRender('404',$content);
	}
	public function msg($success,$error=false,$data=false)
	{
		$msg['success']=$success;
		$msg['error']=$error;
		$msg['data']=$data;
		echo json_encode($msg,JSON_UNESCAPED_UNICODE);
	}
	public function redirect($url)
	{
		header('location: '.$url);
	}

}