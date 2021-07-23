<?php

namespace App\Entity;

use App\Repository\CheckedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CheckedRepository::class)
 */
class Checked
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="checkeds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Todo::class, inversedBy="checked")
     * @ORM\JoinColumn(nullable=false)
     */
    private $todo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTodo(): ?Todo
    {
        return $this->todo;
    }

    public function setTodo(Todo $todo): self
    {
        $this->todo = $todo;

        return $this;
    }
}
