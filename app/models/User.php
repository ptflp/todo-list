<?php
namespace models;

use res\Model as Model;
use entities\User as DoctrineUser;

/**
  * Class User
  */
class User extends Model
{
    public $id;
    public $email;
    public $action;
    public $db;
    public function __construct()
    {
        $this->db=$this->getDoctrine();
        if (isset($_SESSION['uid'])) {
            $id=$_SESSION['uid'];
            $this->authorize($id);
        }
    }
    /*
    * authentication check by login, password
     */
    public function auth($login, $password)
    {
        $user=$this->db->getRepository('entities\User')->findOneBy(['email' => $login]);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $this->id = $user->getId();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /*
    * authorize user by user id
     */
    public function authorize($id=false)
    {
        if (!$id) {
            $id=$this->id;
        }
        $user=$this->db->getRepository('entities\User')->findOneBy(['id' => $id]);
        if (is_object($user)) {
            $this->action=$user;
            $_SESSION['uid']=$user->getId();
            $this->id=$user->getId();
            $this->email=$user->getEmail();
            return true;
        } else {
            return false;
        }
    }
    /*
    * user authorization check by session uid key persistance
     */
    public function isAuthorized()
    {
        return isset($_SESSION['uid']);
    }
    public function userSave($email, $password)
    {
        $db=$this->db;
        try {
            $options = [
                'cost' => 10
            ];
            $hash=password_hash($password, PASSWORD_DEFAULT, $options);
            $user = (new DoctrineUser())
                ->setEmail($email)
                ->setPassword($hash);
            $db->persist($user);
            // Finally flush and execute the database transaction
            $db->flush();
            $this->id=$user->getId();
            $this->email=$user->getEmail();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            return false;
        }
    }
    /*
    * user register by login, password
     */
    public function register($email, $password)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password)>3) {
            if (!$this->isExist($email)) {
                $this->userSave($email, $password);
                $this->authorize($this->id);
                return true;
            } else {
                return false;
            }
        } else {
            $error['error']='попытка внедрения невалидного пользователя: '.$email;
            die(json_encode($error, JSON_UNESCAPED_UNICODE));
        }
    }
    /*
    * user exist check by email
     */
    public function isExist($email)
    {
        $user=$this->db->getRepository('entities\User')->findOneBy(['email' => $email]);
        if (is_object($user)) {
            return true;
        } else {
            return false;
        }
    }
    /*
    * user login by email, password
     */
    public function login($req)
    {
        $user = $this;
        $auth=$user->auth($req['email'], $req['password']);
        if ($auth) {
            $user->authorize($user->id);
            return true;
        } else {
            return false;
        }
    }
    /*
    * user logout function
     */
    public function logout()
    {
        unset($_SESSION['uid']);
    }
}