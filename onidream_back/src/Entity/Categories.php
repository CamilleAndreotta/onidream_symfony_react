<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( ["category:read", "category:show", "excerpt:read", "book:read", "author:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Groups( ["category:read", "category:show", "editor:read", "excerpt:read", "book:read", "author:read"])]
    private ?string $name = null;

    /**
     * @var Collection<int, Excerpts>
     */
    #[ORM\ManyToMany(targetEntity: Excerpts::class, inversedBy: 'categories')]
    #[Groups( ["category:read", "category:show"])]
    private Collection $excerpts;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    private ?Users $users = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    public function __construct()
    {
        $this->excerpts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Excerpts>
     */
    public function getExcerpts(): Collection
    {
        return $this->excerpts;
    }

    public function addExcerpt(Excerpts $excerpt): static
    {
        if (!$this->excerpts->contains($excerpt)) {
            $this->excerpts->add($excerpt);
        }

        return $this;
    }

    public function removeExcerpt(Excerpts $excerpt): static
    {
        $this->excerpts->removeElement($excerpt);

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }
}
