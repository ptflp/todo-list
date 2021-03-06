<?php
namespace entities;

/**
 * @Entity
 * @Table(name="share")
 */
class Share
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="smallint")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $permission;

    /**
     * @Column(type="string", length=255)
     */
    private $user_email;

    /**
     * @Column(type="smallint")
     */
    private $todolist_id;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set permission.
     *
     * @param string $permission
     *
     * @return Share
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission.
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set userEmail.
     *
     * @param string $userEmail
     *
     * @return Share
     */
    public function setUserEmail($userEmail)
    {
        $this->user_email = $userEmail;

        return $this;
    }

    /**
     * Get userEmail.
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * Set todolistId.
     *
     * @param int $todolistId
     *
     * @return Share
     */
    public function setTodolistId($todolistId)
    {
        $this->todolist_id = $todolistId;

        return $this;
    }

    /**
     * Get todolistId.
     *
     * @return int
     */
    public function getTodolistId()
    {
        return $this->todolist_id;
    }
}
