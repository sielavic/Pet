<?php

namespace App\Entity;

//use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={ @ORM\Index(name="fk_user_project", columns={"project_id"})})
 * @ORM\Entity
 */
#[UniqueEntity(fields: ['email'], message: 'Этот  email уже зарегистрирован')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    public function __construct()
    {
        $this->assignedTasks = new ArrayCollection();
        $this->hours = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="roles", type="string", length=50, nullable=true)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     * @Assert\NotBlank
     * @Assert\Regex("/[a-zA-Zа-яА-Я\'\-]/")
     */
    public $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=200, nullable=true)
     * @Assert\NotBlank
     * @Assert\Regex("/[a-zA-Zа-яА-Я\'\-]/")
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Email( message = "email '{{ value }}' не валидный")
     */
    protected $email;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="assignedUser")
     */
    private $assignedTasks;


    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    private $projectName;

    private $projectDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="project_id", type="integer",  nullable=false)
     */
    private $projectId;


    /**
     *
     * @var Collection<int, Hours>
     * @OneToMany(targetEntity="App\Entity\Hours", mappedBy="user")
     */
    private Collection $hours;

    /**
     * @return Collection|Hours[]
     */
    public function getLaborCosts(): Collection
    {
        return $this->hours;
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

    /**
     * @return string|null
     */
    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(?string $projectName): static
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProjectDescription(): ?string
    {
        return $this->projectDescription;
    }

    public function setProjectDescription(?string $projectDescription): static
    {
        $this->projectDescription = $projectDescription;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return Collection|Task[]
     */
    public function getAssignedTasks(): Collection
    {
        return $this->assignedTasks;
    }

    //--- EL USERNAME ES EL EMAIL DEL USUARIO ---
    /* public function getUsername(){
         return $this->email;
     }*/

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return array('ROLE_USER');
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

}