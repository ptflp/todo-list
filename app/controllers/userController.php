<?php
switch (REQURL[1]) {
	case 'login':
		if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
			$user = $TodoApp->user;
			$db = $TodoApp->db;
			$auth=$user->auth($_REQUEST['email'],$_REQUEST['password'],$db);
			if ($auth) {
				$user->authorize($user->id,$db);
				$msg['success']='1';
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			} else {
				$msg['success']='0';
				$msg['error']='Неправильная пара логин пароль';
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			}
		} else {
			if ($TodoApp->user->isAuthorized()) {
				header('location: /');
			}
			include('../view/login.php');
		}
	break;
	case 'logout':
		$TodoApp->user->logout();
		header('location: /user/login');
	break;
	case 'register':
		if ($TodoApp->user->isAuthorized()) {
			header('location: /');
		}
		if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
			$user = $TodoApp->user;
			$db = $TodoApp->db;
			if ($user->register($_REQUEST['email'],$_REQUEST['password'],$db)) {
				$userN=$db->getRepository('entities\User')->findOneBy(['email' => $_REQUEST['email']]);
				$user->authorize($userN->getId(),$db);
				$msg['success']='1';
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			} else {
				$msg['success']='0';
				$msg['error']='данный пользователь уже существует: '.$_REQUEST['email'];
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			}
		} else {
			include('../view/register.php');
		}
	break;

	default:
		require('404.php');
	break;
}