<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
}
$todolist=$TodoApp->user->db->getTodolist();
$todo=new Todo;
$sharedlist=$todo->getShared($TodoApp->user->db,$TodoApp->db);
include('../view/index.php');