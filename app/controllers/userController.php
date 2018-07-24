<?php
switch (REQURL[1]) {
	case 'login':
		if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
			$user = new User($entity_manager);
			$auth=$user->auth($_REQUEST['email'],$_REQUEST['password']);
			if ($auth) {
				$user->authorize($user->id);
				$msg['success']='1';
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			} else {
				$msg['success']='0';
				$msg['error']='Неправильная пара логин пароль';
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			}
		} else {
			if (!empty($_SESSION['uid'])) {
				header('location: /');
			}
			include('../view/login.php');
		}
	break;
	case 'logout':
		session_destroy();
		header('location: /user/login');
	break;
	case 'register':
		if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
			$user= new User();
			if ($user->register($_REQUEST['email'],$_REQUEST['password'])) {
				$userN=$entity_manager->getRepository('entities\User')->findOneBy(['email' => $_REQUEST['email']]);
				$user->authorize($userN->getId());
				$msg['success']='1';
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			} else {
				$msg['success']='0';
				$msg['error']='данный пользователь уже существует: '.$_REQUEST['email'];
				echo json_encode($msg,JSON_UNESCAPED_UNICODE);
			}
		} else {
			if (!empty($_SESSION['uid'])) {
				header('location: /');
			}
			include('../view/register.php');
		}
	break;

	default:
		require('404.php');
	break;
}