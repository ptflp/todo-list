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

}