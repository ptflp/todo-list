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
     * @ManyToOne(targetEntity="User", inversedBy="share")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var \entities\User
     */
    private $user;
    /**
     * @ManyToOne(targetEntity="Todolist", inversedBy="share")
     * @JoinColumn(name="todolist_id", referencedColumnName="id", nullable=false)
     * @var \entities\User
     */
    private $todolist;

    /**
     * Set user.
     *
     * @param \entities\User $user
     *
     * @return Share
     */
    public function setUser(\entities\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \entities\User
     */
    public function getUser()
    {
        return $this->user;
    }

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
}
