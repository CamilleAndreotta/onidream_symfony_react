<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    /**
     * @var Collection<int, Excerpts>
     */
    #[ORM\ManyToMany(targetEntity: Excerpts::class, inversedBy: 'categories')]
    private Collection $excerpts;

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
}
