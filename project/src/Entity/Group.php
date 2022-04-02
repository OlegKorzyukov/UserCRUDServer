<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="group_group")
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @var Collection|Group[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups", cascade={"remove", "persist"})
     */
    private $users;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }
}
