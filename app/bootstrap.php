<?php
require_once "vendor/autoload.php";
require "entities/User.php";
require "entities/Todolist.php";
session_start();
// Setup Doctrine
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