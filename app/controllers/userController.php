<?php
if(!empty(REQURL[1])) {
	switch (REQURL[1]) {
		case 'login':
			if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
				$user = new User($entity_manager);
				$auth=$user->auth($_REQUEST['email'],$_REQUEST['password']);
				if ($auth) {
					$user->authorize($user->id);
				} else {
					$error['error']='Неправильная пара логин пароль';
					echo json_encode($error,JSON_UNESCAPED_UNICODE);
				}
			}
		break;
		case 'logout':
			session_destroy();
			header('location: /user/login');
		break;
		case 'register':
			if (!empty($_SESSION['uid'])) {
				header('location: /');
			}
			if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
				$user= new User();
				if ($user->register($_REQUEST['email'],$_REQUEST['password'])) {
					$userN=$entity_manager->getRepository('entities\User')->findOneBy(['email' => $_REQUEST['email']]);
 					$user->authorize($userN->getId());
				} else {
					$error['error']='данный пользователь уже существует: '.$_REQUEST['email'];
					echo json_encode($error,JSON_UNESCAPED_UNICODE);
				}
			}
		break;

		default:
			header('location: /');
		break;
	}
}