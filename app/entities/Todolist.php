<?php
namespace entities;

/**
 * @Entity
 * @Table(name="todolist",options={"collate"="utf8mb4_unicode_ci","charset":"utf8mb4"})
 */
class Todolist
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
    private $title;

    /**
     * @Column(type="text")
     */
    private $tasks;

    /**
     * @Column(type="datetime")
     */
    private $date;

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
     * Set title.
     *
     * @param string $title
     *
     * @return Todolist
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set tasks.
     *
     * @param string $tasks
     *
     * @return Todolist
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Get tasks.
     *
     * @return string
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Todolist
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * @ManyToOne(targetEntity="User", inversedBy="todolist")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var \entities\User
     */
    private $user;

    /**
     * Set user.
     *
     * @param \entities\User $user
     *
     * @return Todolist
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
}
