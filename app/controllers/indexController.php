<?php
if (empty($_SESSION['uid'])) {
	header('location: /user/login');
}
$todolist=$TodoApp->user->db->getTodolist();
$todo=new Todo;
$shared=$todo->getShared($TodoApp->user->db,$TodoApp->db);
// dump_r($shared);
include('../view/index.php');