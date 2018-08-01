<?php
namespace models;
use res\Model as Model;
use entities\Todolist;
use entities\Share;
use \DateTime;
use \stdClass;
/**
  * Class Todo
  */
 class Todo extends Model
 {
 	public $db;
 	public $data;
 	public $mustache;
 	/*
 	* Sets user permission of todo list by email
 	 */
 	public function setPermission($todolist_id,$user_email,$permission)
 	{
 		$db = Model::getDoctrine();
		$perm=$this->checkPermByEmail($todolist_id,$user_email,$db);
		if ($perm) {
 			$query=$db->createQueryBuilder();
		    $result = $query->select('p')
	            ->from('entities\Share', 'p')
	            ->where('p.user_email= :user_email')
	            ->setParameter('user_email', $user_email)
	            ->andWhere('p.todolist_id= :todolist_id')
	            ->setParameter('todolist_id', $todolist_id)
	            ->getQuery()
	            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
	        $id=$result[0]['id'];
			$share = $db->getRepository('entities\Share')->findOneBy(['id' =>$id]);
			$share->setPermission($permission);
			$db->merge($share);
			$db->flush();
			$this->setData(	1,
							$share->getPermission(),
							$share->getUserEmail(),
							$this->db->getTitle(),
							json_decode($this->db->getTasks())
						);
			return true;
		} else {
			$share = (new Share())
			    ->setPermission($permission)
			    ->setUserEmail($user_email)
			    ->setTodolistId($todolist_id);
			$db->persist($share);
			$db->flush();
			$this->setData(	1,
							$share->getPermission(),
							$share->getUserEmail(),
							$this->db->getTitle(),
							json_decode($this->db->getTasks())
						);
			return true;
		}
 	}
 	/*
 	* Data for output
 	 */
 	public function setData($success,$perm=false,$email=false,$title=false,$data=false)
 	{
			$msg['success']=$success;
			$msg['permission']=$perm;
			$msg['email']=$email;
			$msg['title']=$title;
			$msg['data']=$data;
			$this->data=$msg;
 	}
 	/*
 	* Remove user todo list by id
 	 */
 	public function todoRemove($id,$uid)
 	{
 		$db = Model::getDoctrine();
 		$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
 		$email=$user->getEmail();
		$perm=$this->checkPermByEmail($id,$email); // Check perm for writing
		if ($perm==1) {
			// Create a new task
			$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' =>$id]);
			if (is_object($todo)) {
				// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
				// don't need to persist the task separately: it will be persisted when persisting
				// the User
				$user->removeTodolist($todo);
				// Finally flush and execute the database transaction
				$db->flush();
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
 	}
 	/*
 	* Gets user permission of todo list by email
 	 */
 	public function checkPermByEmail($todolist_id,$user_email)
 	{
 		try {
 			$db = Model::getDoctrine();
 			$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' => $todolist_id]);
 			$this->db=$todo;
 			if(is_object($todo)) {
 				$user=$todo->getUser();
 				if ($user->getEmail()==$user_email) {
 					return 1;
 				} else {
		 			$query=$db->createQueryBuilder();
				    $result = $query->select('p')
			            ->from('entities\Share', 'p')
			            ->where('p.user_email= :user_email')
			            ->setParameter('user_email', $user_email)
			            ->andWhere('p.todolist_id= :todolist_id')
			            ->setParameter('todolist_id', $todolist_id)
			            ->getQuery()
			            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
			        if (!empty($result)) {
		 				return $permission=$result[0]['permission'];
			        } else {
			        	return false;
			        }
 				}
 			} else {
 				return false;
		    }
 		} catch (Doctrine\DBAL\DBALException $e) {
 			echo 'error';
 		}
 	}
 	/*
 	* Gets user shared todo list by user id
 	 */
 	public function getShared($uid)
 	{
 		$db = Model::getDoctrine();
 		$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
 		$email=$user->getEmail();
 		$shared = $db->getRepository('entities\Share')->findBy(['user_email' => $email]);
 		$arrItems=[];
 		$perm=[];
 		foreach ($shared as $item) {
 			$id=$item->getTodolistId();
 			$arrItems[]=$id;
 			$perm[$id]=$item->getPermission();
 		}
 		$data['perm']=$perm;
 		$data['todo'] = $db->getRepository('entities\Todolist')->findBy(['id' => $arrItems]);
		$sharedList=[];
		foreach ($data['todo'] as $todo){
			$id=$todo->getId();
			$perm=$data['perm'][$id];
			switch ($perm) {
				case 2:
					$perm='Read/Write';
					break;
				default:
					$perm='Read';
					break;
			}
			$sharedList[]=['title'=>$todo->getTitle(),'id'=>$todo->getId(),'perm'=>$perm];
		}
 		return $sharedList;
 	}
 	/*
 	* Gets user todo list by user id
 	 */
 	public function getUserTodo($uid)
 	{
 		$db = Model::getDoctrine();
 		$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
		$data=$user->getTodolist();
		$todoList=[];
		foreach ($data as $todo) {
			$todoList[]=['title'=>$todo->getTitle(),'id'=>$todo->getId()];
		}
		return $todoList;
 	}
 	/*
 	* Creating new todo
 	 */
 	public function createTodo($uid,$title)
 	{
		try {
			$db = Model::getDoctrine();
			$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
			// Create a new todolist
			$todo = (new Todolist())
			    ->setTitle($title)
			    ->setTasks("[]")
			    ->setUser($user)
			    ->setDate(new DateTime());

			// Add the todolist the to list of the User tasks. Since we used cascade={"all"}, we
			// don't need to persist the todolist separately: it will be persisted when persisting
			// the User
			$user->addTodolist($todo);
			$db->persist($todo);

			// Finally flush and execute the database transaction
			$db->flush();
			return true;
		} catch (Doctrine\DBAL\DBALException $e) {
			$this->data=$e;
			return false;
		}
 	}
 	/*
 	* Get user todo tasks
 	*/
 	public function getUserTasks($id,$uid)
 	{
		try {
			$db = Model::getDoctrine();
			$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
			$email=$user->getEmail();
			$perm=$this->checkPermByEmail($id,$email); // Check perm for writing
			if ($perm) {
				$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' => $id]);
				if(is_object($todo)){
					$title=$todo->getTitle();
					$data = json_decode($todo->getTasks());
					$this->setData(1,$perm,$email,$title,$data);
					return true;
				} else {
					return false;
				}
			}
		} catch (Doctrine\DBAL\DBALException $e) {
			die('something went wrong');
		}
 	}
 	/*
 	* Save user todo tasks
 	*/
 	public function saveUserTasks($id,$data,$uid)
 	{
		$db = Model::getDoctrine();
		$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
		$email=$user->getEmail();
		$perm=$this->checkPermByEmail($id,$email); // Check perm for writing
		if ($perm==1 || $perm==2) {
			$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' =>$id]);
			if($todo){
				$user=$todo->getUser();
				$array=json_decode($_REQUEST['data']);
				if (is_array($array)) {
					$arr=[];
					$i=0;
					foreach ($array as $key => $value) {
						$item = new stdClass();
						$item->title=$value->title;
						$item->complete=$value->complete;
						$item->id=$value->id;
						if($value->title && mb_strlen($value->id)== 17 && is_bool($value->complete)){
							$arr[]=$item;
						}
					}
					$json=json_encode($arr);
					$todo->setTasks($json);
					$db->merge($todo);
					$db->flush();
					$title=$todo->getTitle();
					$data = json_decode($json);
					$this->setData(1,$perm,$email,$title,$data);
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
 	}
 	/*
 	* Edit user todo title
 	*/
 	public function editTodoTitle($id,$title,$uid)
 	{
		try {
			$db = Model::getDoctrine();
			$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
			$email=$user->getEmail();
			$perm=$this->checkPermByEmail($id,$email); // Check perm for writing
			if ($perm==1) {
				$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' =>$id]);
				$Tuser=$todo->getUser();
				if(is_object($todo) && $Tuser->getId() == $user->getId()){
					$todo->setTitle($title);
					$db->merge($todo);
					$db->flush();
					$data = json_decode($todo->getTasks());
					$this->setData(1,$perm,$email,$title,$data);
					return true;
				} else {
				return false;
				}
			} else {
				return false;
			}
		} catch (Doctrine\DBAL\DBALException $e) {
			die('something went wrong'.$e);
		}
 	}
 	/*
 	* Share user todo with other users by email
 	*/
 	public function shareTodo($id,$email,$perm,$uid)
 	{
 		if (filter_var($email, FILTER_VALIDATE_EMAIL) && is_numeric($perm)) {
 			$permission=[2,3];
 			if (!in_array($perm,$permission)) {
				header('location: /');
 			}
			try {
				$db = Model::getDoctrine();
				$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
				$uemail=$user->getEmail();
				$uperm=$this->checkPermByEmail($id,$uemail); // Check perm for writing
				if ($uperm==1) {
					if($uemail==$email) {
						$this->setData(0);
						return false;
					} else {
						$this->setPermission($id,$email,$perm);
						return true;
					}
				} else {
					$this->setData(0);
					return false;
				}
			} catch (Doctrine\DBAL\DBALException $e) {
				die('something went wrong'.$e);
			}
		}
 	}
 	public function getUserTodoBy($param=false)
 	{
		try {
			$uid=$param['uid'];
			$id=$param['id'];
			$db = Model::getDoctrine();
			$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
			$todo=$this;
			$email=$user->getEmail();
			$perm=$todo->checkPermByEmail($id,$email); // Check perm for writing
			if ($perm) {
				$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' => $id]);
				if($todo){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} catch (Doctrine\DBAL\DBALException $e) {
			die('something went wrong');
		}
 	}

 }