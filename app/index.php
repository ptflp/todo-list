<?php
require "bootstrap.php";
require "entities/User.php";
require "entities/Todolist.php";

// Create and persist a new User
$user = (new entities\User())
    ->setEmail("John")
    ->setPassword("Doe");
// $user = $entity_manager->getRepository('entities\User')->findOneBy(['id' => 2]);
$entity_manager->persist($user);

// Create a new task
$task = (new entities\Todolist())
    ->setTitle("Hello Wold")
    ->setTasks("This is a test task")
    ->setUser($user)
    ->setDate(new DateTime());

// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
// don't need to persist the task separately: it will be persisted when persisting
// the User
$user->addTodolist($task);

// Finally flush and execute the database transaction
$entity_manager->flush();