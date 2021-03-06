<?php
/**
 * Controller
 */
namespace res;
{
	abstract class Controller
	{
		public $view;
		public $route;
		function __construct()
		{
			$this->view = new View();
	 		if (!empty($_GET['__route'])) {
	 			$this->route = trim($_GET['__route'],'/');
	 		} else {
				$this->route = '/';
	 		}
		}
	}
}
