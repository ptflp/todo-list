<?php
use controllers\AppController;
use res\Controller;
use models\Todo;
use models\User;
/**
 * Api Controller
 */
class ApiController extends AppController
{
	public $todo;
	function __construct()
	{
		parent::__construct();
		$this->todo = new Todo();
	}
	/*
	* Creating new todo list with title
	 */
	public function actionCreate()
	{
		$this->checkParam(['request'=>['title']]);
		$todo = $this->todo;
		$uid = $this->user->id;
		if ($todo->createTodo($uid,$_REQUEST['title'])) {
			$this->msg(1);
		} else {
			$this->msg(0);
		}
	}
	/*
	* remove todo list by id
	 */
	public function actionRemove($id=false)
	{
		$this->checkParam(['id'=>$id]);
		$todo = $this->todo;
		$uid=$this->user->id;
		if ($todo->todoRemove($id,$uid)) {
			$this->msg(1);
		} else {
			$this->msg(0);
		}
	}
	/*
	* Save user todo tasks by id
	 */
	public function actionSave($id=false)
	{
		$this->checkParam(['id'=>$id,'request'=>['data']]);
		$todo = $this->todo;
		$data = $_REQUEST['data'];
		$uid = $this->user->id;
		if ($todo->saveUserTasks($id,$data,$uid)) {
			echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
		} else {
			$this->msg(0);
		}
	}
	/*
	* Get user todo tasks by id
	 */
	public function actionGet($id=false)
	{
		$this->checkParam(['id'=>$id]);
		$todo = $this->todo;
		$uid = $this->user->id;
		if ($todo->getUserTasks($id,$uid)) {
			echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
		} else {
			$this->msg(0);
		}
	}
	/*
	* Edit todo title by id
	 */
	public function actionEdit($id=false)
	{
		$this->checkParam(['id'=>$id,'request'=>['title']]);
		$todo = $this->todo;
		$title=$_REQUEST['title'];
		$uid = $this->user->id;
		if ($todo->editTodoTitle($id,$title,$uid)) {
			echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
		} else {
			$this->msg(0);
		}
	}
	/*
	* Edit todo title by id
	 */
	public function actionShare($id=false)
	{
		$this->checkParam(['id'=>$id,'request'=>['email','permission']]);
		$todo = $this->todo;
		$email = $_REQUEST['email'];
		$perm = $_REQUEST['permission'];
		$uid = $this->user->id;
		if ($todo->shareTodo($id,$email,$perm,$uid)) {
			echo json_encode($todo->data,JSON_UNESCAPED_UNICODE);
		} else {
			$this->msg(0);
		}
	}
	/*
	* Frontend api url initialization
	 */
	public function actionSettings()
	{
		if(isset($_REQUEST['settings'])) {
			$url['create']="/api/create/";
			$url['get']="/api/get/";
			$url['edit']="/api/edit/";
			$url['save']="/api/save/";
			$url['remove']="/api/remove/";
			$url['share']="/api/share/";
			echo json_encode($url,JSON_UNESCAPED_UNICODE);
		} else {
			$this->notFound();
		}
	}
	/*
	* Register user by email, password
	 */
	public function actionRegister()
	{
		$this->checkParam(['request'=>['email','password']]);
		$user = new User;
		if ($user->register($_REQUEST['email'],$_REQUEST['password'])) {
			$this->msg(1);
		} else {
			$this->msg(0);
		}
	}
	/*
	* Login user by email, password
	 */
	public function actionLogin()
	{
		$user = new User;
		$this->checkParam(['request'=>['email','password']]);
		$login = $user->login([
			'email' => $_REQUEST['email'],
			'password' => $_REQUEST['password']
		]);
		if ($login) {
			$this->msg(1);
		} else {
			$this->msg(0);
		}
	}
}