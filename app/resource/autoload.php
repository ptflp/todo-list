<?php
require_once "../vendor/autoload.php";
spl_autoload_register(function ($class_name) {
    include 'classes/'.$class_name . '.php';
});