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

}