<?php
defined('APP_ENV') or define('APP_ENV', 'dev');
switch (APP_ENV) {
	case 'dev':
		error_reporting(E_ALL & ~E_NOTICE);
		break;
	case 'production':
		error_reporting(0);
		break;
	default:
		error_reporting(0);
		break;
}
require('../core.php');