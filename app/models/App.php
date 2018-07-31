<?php
namespace models;
use res\Model as Model;
/**
  * Class App
  */
 class App
 {
 	public $db;
 	public $user;
 	function __construct($configuration=false,$connection_parameters=false)
 	{
		// Setup Doctrine
		// Setup connection parameters
		// Get the entity manager
		$this->db = Doctrine\ORM\EntityManager::create($connection_parameters, $configuration);
 	}
 } ?>