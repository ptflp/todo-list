<?php
namespace models;
use res\Model as Model;
use entities\Todolist;
use \DateTime;
/**
  * Class Todo
  */
 class Todo extends Model
 {
 	public $db;
 	public $data;
 	public $mustache;
 	public function setPermission($todolist_id,$user_email,$permission,$db)
 	{
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
			$this->data=$msg;
			return true;
		} else {
			$share = (new entities\Share())
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
 	public function setData($success,$perm,$email,$title,$data)
 	{
			$msg['success']=$success;
			$msg['permission']=$perm;
			$msg['email']=$email;
			$msg['title']=$title;
			$msg['data']=$data;
			$this->data=$msg;
 	}
 	public function todoRemove($id,$uid)
 	{
 		$db = Model::getDoctrine();
 		$user=$db->getRepository('entities\User')->findOneBy(['id' => $uid]);
 		$email=$user->getEmail();
		$perm=$this->checkPermByEmail($id,$email); // Check perm for writing
		if ($perm==1) {
			$user=$db;
			// Create a new task
			$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' =>$id]);
			if (is_object($todo)) {
				// Add the task the to list of the User tasks. Since we used cascade={"all"}, we
				// don't need to persist the task separately: it will be persisted when persisting
				// the User
				$user->removeTodolist($todo);
				// Finally flush and execute the database transaction
				$db->persist($todo);
				$db->flush();
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
 	}
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

 }