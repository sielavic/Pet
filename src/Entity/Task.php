<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Validator\Constraints\File;


/**
 * Task
 *
 * @ORM\Table(name="tasks", indexes={@ORM\Index(name="fk_task_user", columns={"user_id"}), @ORM\Index(name="fk_task_project", columns={"project_id"})})
 * @ORM\Entity
 */
class Task
{

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->laborCosts = new ArrayCollection();
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

    /**
     * @var string|null
     *
     * @ORM\Column(name="priority", type="string", length=20, nullable=true)
     */
    private $priority;

    /**
     * @var int|null
     *
     * @ORM\Column(name="hours", type="integer", nullable=true)
     */
    private $hours;

    /**
     * @var string|null
     *
     * @ORM\Column(name="state", type="string", nullable=true)
     */
    private $state;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="assigned_user_id", referencedColumnName="id")
     * })
     */
    private $assignedUser;


    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;


    /**
     * @var Collection[]
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="parentId")
     */
    private $children;


    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="children")
     * @ORM\JoinColumns({
     *    @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *  })
     */
    private ?Task $parentId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="project_id", type="integer",  nullable=false)
     */
    private $projectId;


    /**
     *
     * @var Collection<int, Hours>
     * @OneToMany(targetEntity="App\Entity\Hours", mappedBy="task")
     */
    private Collection $laborCosts;


    /**
     * @return Collection|Hours[]
     */
    public function getLaborCosts(): Collection
    {
        return $this->laborCosts;
    }


    public function getHoursAll()
    {
        $hours = 0;
        $laborCosts = $this->laborCosts;
        /** @var Hours $laborCost */
        foreach ($laborCosts as $laborCost) {
            $hours += $laborCost->getHours();
        }

        return $hours;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    /* @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /* @param mixed $parentId
     */
    public function setParentId($parentId): void
    {
        $this->parentId = $parentId;
    }

    /* @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /* @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): static
    {
        $this->file = $file;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(?string $priority): static
    {
        $this->priority = $priority;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

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

    public function getUserId(): ?int
    {
        return $this->userid;
    }

    public function setUserId(?int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAssignedUser(): ?User
    {
        return $this->assignedUser;
    }

    public function setAssignedUser(?User $assignedUser): self
    {
        $this->assignedUser = $assignedUser;

        return $this;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function setAssignedUserName($assignedusername): self
    {
        $this->assignedusername = $assignedusername;

        return $this;
    }

}