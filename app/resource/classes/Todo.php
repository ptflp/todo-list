<?php
/**
  * Class Todo
  */
 class Todo
 {
 	public function checkPermByEmail($todolist_id,$user_email,$db)
 	{
 		try {
 			$todo = $db->getRepository('entities\Todolist')->findOneBy(['id' => $todolist_id]);
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
			        if (!empty($share)) {
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