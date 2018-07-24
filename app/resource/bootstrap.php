<?php
require_once "../resource/autoload.php";
require "../entities/User.php";
require "../entities/Todolist.php";
session_start();

$configuration = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $paths = [__DIR__ . '/entities'],
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