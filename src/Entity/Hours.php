<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Validator\Constraints\File;


/**
 * Hours
 *
 * @ORM\Table(name="labor_costs", indexes={@ORM\Index(name="fk_hours_user", columns={"user_id"}), @ORM\Index(name="fk_hours_task", columns={"task_id"})})
 * @ORM\Entity
 */
class Hours
{


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var int
     *
     * @ORM\Column(name="task_id", type="integer", nullable=false)
     */
    private $taskid;


    /**
     *
     * @ManyToOne(targetEntity="App\Entity\User", inversedBy="hours")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User|null $user = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;


    /**
     * @var int|null
     *
     * @ORM\Column(name="hours", type="integer", nullable=true)
     */
    private $hours;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     *
     * @ManyToOne(targetEntity="Task", inversedBy="hours")
     * @JoinColumn(name="task_id", referencedColumnName="id")
     */
    private Task|null $task = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userid;
    }

    public function setUserId(?int $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    public function getTaskId(): ?string
    {
        return $this->taskid;
    }

    public function setTaskId(?int $taskid): static
    {
        $this->taskid = $taskid;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }


    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(?int $hours): static
    {
        $this->hours = $hours;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }


    public function getDateString()
    {
        $date = $this->date;
        $newDate = $date->format('Y-m-d');

        return $newDate;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }


    public function getTask()
    {
        return $this->task;
    }


    public function setTask($task): void
    {
        $this->task = $task;
    }


    public function getUser()
    {
        return $this->user;
    }


    public function setUser($user): void
    {
        $this->user = $user;
    }


}