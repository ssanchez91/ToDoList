<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("username")
 */
class User implements UserInterface
{
    /**
     * @var Int
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var String
     * 
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @var String
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var String
     * 
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="Author", orphanRemoval=true)
     */
    private $tasks;

    /**
     * @var Array
     * 
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * __construct User class
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return Int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @see  UserInterface
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param String $username
     * @return void
     */
    public function setUsername(String $username)
    {
        $this->username = $username;
    }

    /**
     * @see  UserInterface
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param String $password
     * @return void
     */
    public function setPassword(String $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param String $email
     * @return void
     */
    public function setEmail(String $email): void
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see  UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     *
     * @return $this
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Task $task
     *
     * @return $this
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAuthor() === $this) {
                $task->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
