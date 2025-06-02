<?php

namespace App\Entity;

use App\Repository\AuthorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AuthorsRepository::class)]
class Authors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( ["author:read", "category:read", "book:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Groups( ["author:read", "category:read", "book:read", "editor:read", "category:show", "excerpt:read"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 200)]
    #[Groups( ["author:read", "category:read", "book:read", "editor:read", "category:show", "excerpt:read"])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups( ["author:read", "category:read", "book:read", "editor:read", "category:show", "excerpt:read"])]
    private ?\DateTimeInterface $birthDate = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups( ["author:read", "category:read", "book:read", "editor:read", "category:show", "excerpt:read"])]
    private ?\DateTimeInterface $deathDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups( ["author:read", "category:read", "book:read", "editor:read", "category:show", "excerpt:read"])]
    private ?string $biography = null;

    /**
     * @var Collection<int, Books>
     */
    #[ORM\ManyToMany(targetEntity: Books::class, inversedBy: 'authors')]
    #[Groups( ["author:read"])]
    private Collection $book;

    #[ORM\ManyToOne(inversedBy: 'authors')]
    private ?Users $users = null;


    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Books $book): static
    {
        if (!$this->book->contains($book)) {
            $this->book->add($book);
        }

        return $this;
    }

    public function removeBook(Books $book): static
    {
        $this->book->removeElement($book);

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

    public function getDeathDate(): ?\DateTimeInterface
    {
        return $this->deathDate;
    }

    public function setDeathDate(?\DateTimeInterface $deathDate): static
    {
        $this->deathDate = $deathDate;

        return $this;
    }
}
