<?php
if(!empty(REQURL[1])) {
	switch (REQURL[1]) {
		case 'login':
			if (isset($_GET['email']) && isset($_GET['password'])) {
				$user = new User($entity_manager);
				$auth=$user->auth($_GET['email'],$_GET['password']);
				if ($auth) {
					$user->authorize($user->id);
				} else {
					echo 'Неправильная пара логин пароль';
				}
			}
			if (!empty($_SESSION['uid'])) {
				header('location: /');
			}
		break;
		case 'logout':
			session_destroy();
			header('location: /user/login');
		break;
		case 'register':
			echo $_SESSION['uid'];
			if (isset($_GET['email']) && isset($_GET['password'])) {
				$user= new User();
				$user->register($_GET['email'],$_GET['password']);
 				$userN=$entity_manager->getRepository('entities\User')->findOneBy(['email' => $_GET['email']]);
 				$user->authorize($userN->getId());
			}
		break;

		default:
			header('location: /');
		break;
	}
}