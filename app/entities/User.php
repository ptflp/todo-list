<?php
namespace entities;

/**
 * @Entity
 * @Table(name="user")
 */
class User
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="smallint")
     */
    private $id;

    /**
     * @Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @Column(type="string")
     */
    private $password;

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
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @OneToMany(targetEntity="Todolist", mappedBy="user", cascade={"all"})
     * @var Doctrine\Common\Collection\ArrayCollection
     */
    private $todolist;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->todolist = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add todolist.
     *
     * @param \entities\Todolist $todolist
     *
     * @return User
     */
    public function addTodolist(\entities\Todolist $todolist)
    {
        $this->todolist[] = $todolist;

        return $this;
    }

    /**
     * Remove todolist.
     *
     * @param \entities\Todolist $todolist
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTodolist(\entities\Todolist $todolist)
    {
        return $this->todolist->removeElement($todolist);
    }

    /**
     * Get todolist.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTodolist()
    {
        return $this->todolist;
    }
}
