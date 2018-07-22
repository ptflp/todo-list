<?php
/**
  * Class User
  */
 class User
 {
 	public $id;
 	public $email;
 	protected $entity_manager;
 	public function auth($login,$password)
 	{
 		$user=$this->entity_manager->getRepository('entities\User')->findOneBy(['email' => $login]);
		if(password_verify($password, $user->getPassword())) {
			$this->id = $user->getId();
		    return true;
		} else {
			return false;
		}
 	}
 	public function authorize($id)
 	{
 		$user=$this->entity_manager->getRepository('entities\User')->findOneBy(['id' => $id]);
 		$_SESSION['uid']=$user->getId();
 		$this->id=$user->getId();
 		$this->email=$user->getEmail();
 	}
 	public function isAuthorized()
 	{
 		if ($this->id == $_SESSION['uid']) {
 			return true;
 		} else {
 			return false;
 		}
 	}
 	public function register($login,$password)
 	{
 		$options = [
		    'cost' => 10
		];
		$hash=password_hash($password, PASSWORD_DEFAULT,$options);
		$user = (new entities\User())
		    ->setEmail($login)
		    ->setPassword($hash);
		// $user = $entity_manager->getRepository('entities\User')->findOneBy(['id' => 2]);
		$this->entity_manager->persist($user);
		// Finally flush and execute the database transaction
		$this->entity_manager->flush();
 	}
 	function __construct()
 	{
 		$configuration = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
		    $paths = [__DIR__ . '/entities'],
		    $isDevMode = true
		);

		// Setup connection parameters
		$connection_parameters = [
		    'dbname' => 'appdb',
		    'user' => 'root',
		    'password' => 'root',
		    'host' => 'appdb',
		    'driver' => 'pdo_mysql'
		];

		// Get the entity manager
		$entity_manager = Doctrine\ORM\EntityManager::create($connection_parameters, $configuration);
 		$this->entity_manager=$entity_manager;
 		if (!empty($_SESSION['uid'])) {
 			$this->authorize($_SESSION['uid']);
 		}
 	}
 }