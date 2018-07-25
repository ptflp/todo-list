<?php
require_once "/var/www/resource/autoload.php";
require "/var/www/entities/User.php";
require "/var/www/entities/Todolist.php";
require "/var/www/entities/Share.php";
session_start();

$configuration = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $paths = ['/var/www/entities/'],
    $isDevMode = true
);
$connection_parameters = [
    'dbname' => 'appdb',
    'user' => 'root',
    'password' => 'root',
    'host' => 'appdb',
    'driver' => 'pdo_mysql'
];
$TodoApp = new App($configuration,$connection_parameters);