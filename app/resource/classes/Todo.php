<?php
/**
  * Class Todo
  */
 class Todo
 {
 	public $db;
 	public $data;
 	public function setPermission($todolist_id,$user_email,$permission,$db)
 	{
		$perm=$this->checkPermByEmail($todolist_id,$user_email,$db);
		if ($perm) {
			$share = $db->getRepository('entities\Share')->findOneBy(['user_email' =>$user_email]);
			$share->setPermission($permission);
			$db->merge($share);
			$db->flush();
			$msg['success']=1;
			$msg['permission']=$share->getPermission();
			$msg['email']=$share->getUserEmail();
			$msg['title']=$this->db->getTitle();
			$msg['data']=json_decode($this->db->getTasks());
			$this->data=$msg;
			return true;
		} else {
			$share = (new entities\Share())
			    ->setPermission($permission)
			    ->setUserEmail($user_email)
			    ->setTodolistId($todolist_id);
			$db->persist($share);
			$db->flush();
			$msg['success']=1;
			$msg['permission']=$share->getPermission();
			$msg['email']=$share->getUserEmail();
			$msg['title']=$this->db->getTitle();
			$msg['data']=json_decode($this->db->getTasks());
			$this->data=$msg;
			return true;
		}
 	}
 	public function checkPermByEmail($todolist_id,$user_email,$db)
 	{
 		try {
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

 }