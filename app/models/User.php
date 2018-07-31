<?php
namespace models;
use res\Model as Model;
/**
  * Class User
  */
 class User extends Model
 {
 	public $id;
 	public $email;
 	function __construct()
 	{
 		if (isset($_SESSION['uid'])) {
 			$this->authorize($id);
 		}
 	}
 	public static function auth($login,$password,$db)
 	{
 		$user=$db->getRepository('entities\User')->findOneBy(['email' => $login]);
 		if ($user) {
 			if(password_verify($password, $user->getPassword())) {
				$this->id = $user->getId();
			    return true;
			} else {
				return false;
			}
 		} else {
 			return false;
 		}
 	}
 	public function authorize($id)
 	{
 		$user=$db->getRepository('entities\User')->findOneBy(['id' => $id]);
 		$this->db=$user;
 		$_SESSION['uid']=$user->getId();
 		$this->id=$user->getId();
 		$this->email=$user->getEmail();
 	}
 	public function isAuthorized()
 	{
 		return isset($_SESSION['uid']);
 	}
 	public function register($login,$password)
 	{
 		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
	 		try {
		 		$options = [
				    'cost' => 10
				];
				$hash=password_hash($password, PASSWORD_DEFAULT,$options);
				$user = (new entities\User())
				    ->setEmail($login)
				    ->setPassword($hash);
				$db->persist($user);
				// Finally flush and execute the database transaction
				$db->flush();
				return true;
	 		} catch (Doctrine\DBAL\DBALException $e) {
	 			return false;
	 		}
 		} else {
 			$error['error']='попытка внедрения невалидного пользователя: '.$login;
 			die(json_encode($error,JSON_UNESCAPED_UNICODE));
 		}
 	}
 	public function logout()
 	{
 		unset($_SESSION['uid']);
 	}
 }