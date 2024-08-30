<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\File;


/**
 * Task
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 */
class Project
{

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string|null
     *
     * @ORM\Column(name="project_name", type="string", length=255, nullable=true)
     */
    private $projectName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;


    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * @var Collection[]
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="project")
     */
    private $tasks;

    /**
     * @var Collection[]
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="project")
     */
    private $users;


    /* @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /* @param mixed $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
    }


    /* @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }


    /* @param mixed $tasks
     */
    public function setTasks($tasks): void
    {
        $this->tasks = $tasks;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(?string $projectName): static
    {
        $this->projectName = $projectName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}