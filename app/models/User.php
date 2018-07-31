<?php
namespace models;
use res\Model as Model;
use entities\User as dUser;
/**
  * Class User
  */
 class User extends Model
 {
 	public $id;
 	public $email;
 	public $action;
 	public $db;
 	function __construct()
 	{
 		$this->db=$this->getDoctrine();
 		if (isset($_SESSION['uid'])) {
 			$id=$_SESSION['uid'];
 			$this->authorize($id);
 		}
 	}
 	public function auth($login,$password)
 	{
 		$user=$this->db->getRepository('entities\User')->findOneBy(['email' => $login]);
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
 	public function authorize($id=false)
 	{
 		if (!$id) {
 			$id=$this->id;
 		}
 		$user=$this->db->getRepository('entities\User')->findOneBy(['id' => $id]);
 		if (is_object($user)) {
	 		$this->action=$user;
	 		$_SESSION['uid']=$user->getId();
	 		$this->id=$user->getId();
	 		$this->email=$user->getEmail();
	 		return true;
 		} else {
 			return false;
 		}
 	}
 	public function isAuthorized()
 	{
 		return isset($_SESSION['uid']);
 	}
 	public function register($login,$password)
 	{
 		if (filter_var($login, FILTER_VALIDATE_EMAIL) && strlen($password)<3) {
 			$db=$this->db;
	 		try {
		 		$options = [
				    'cost' => 10
				];
				$hash=password_hash($password, PASSWORD_DEFAULT,$options);
				$user = (new dUser())
				    ->setEmail($login)
				    ->setPassword($hash);
				$db->persist($user);
				// Finally flush and execute the database transaction
				$db->flush();
 				$this->id=$user->getId();
 				$this->email=$user->getEmail();
				return true;
	 		} catch (Doctrine\DBAL\DBALException $e) {
	 			return false;
	 		}
 		} else {
 			$error['error']='попытка внедрения невалидного пользователя: '.$login;
 			die(json_encode($error,JSON_UNESCAPED_UNICODE));
 		}
 	}
 	public function isExist($login)
 	{
 		$user=$this->db->getRepository('entities\User')->findOneBy(['email' => $login]);
 		if (is_object($user)) {
 			return true;
 		} else {
 			return false;
 		}
 	}
 	public function logout()
 	{
 		unset($_SESSION['uid']);
 	}
 }