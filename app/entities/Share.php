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
     * @ManyToOne(targetEntity="Todolist", inversedBy="share")
     * @JoinColumn(name="todolist_id", referencedColumnName="id", nullable=false)
     * @var \entities\User
     */
    private $todolist;

    /**
     * Set todolist.
     *
     * @param \entities\Todolist $todolist
     *
     * @return Share
     */
    public function setTodolist(\entities\Todolist $todolist)
    {
        $this->todolist = $todolist;

        return $this;
    }

    /**
     * Get todolist.
     *
     * @return \entities\Todolist
     */
    public function getTodolist()
    {
        return $this->todolist;
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
}
