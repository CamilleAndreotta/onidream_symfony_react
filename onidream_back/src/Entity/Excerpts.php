<?php

namespace App\Entity;

use App\Repository\ExcerptsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ExcerptsRepository::class)]
class Excerpts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( ["excerpt:read", "category:read", "author:read", "book:read", "editor:read", "category:show"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups( ["excerpt:read", "category:read", "author:read", "book:read", "editor:read", "category:show"])]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups( ["excerpt:read","category:read", "author:read", "book:read", "editor:read", "category:show"])]
    private ?\DateTimeInterface $bookStartedOn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups( ["excerpt:read", "category:read", "author:read", "book:read", "editor:read", "category:show"])]
    private ?\DateTimeInterface $bookEndedOn = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups( ["excerpt:read", "category:read", "author:read", "book:read", "editor:read","category:show"])]
    private ?string $bookPage = null;

    #[ORM\ManyToOne(inversedBy: 'excerpts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups( ["excerpt:read", "category:read", "category:show"])]
    private ?Books $books = null;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'excerpts')]
    #[Groups( ["excerpt:read", "editor:read", "book:read", "author:read"])]
    #[MaxDepth(1)]
    private Collection $categories;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'excerpts')]
    private Collection $users;

    #[ORM\Column(nullable: true)]
    private ?int $creatorId = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getBookStartedOn(): ?\DateTimeInterface
    {
        return $this->bookStartedOn;
    }

    public function setBookStartedOn(\DateTimeInterface $bookStartedOn): static
    {
        $this->bookStartedOn = $bookStartedOn;

        return $this;
    }

    public function getBookEndedOn(): ?\DateTimeInterface
    {
        return $this->bookEndedOn;
    }

    public function setBookEndedOn(\DateTimeInterface $bookEndedOn): static
    {
        $this->bookEndedOn = $bookEndedOn;

        return $this;
    }

    public function getBookPage(): ?string
    {
        return $this->bookPage;
    }

    public function setBookPage(?string $bookPage): static
    {
        $this->bookPage = $bookPage;

        return $this;
    }

    public function getBooks(): ?Books
    {
        return $this->books;
    }

    public function setBooks(?Books $books): static
    {
        $this->books = $books;

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addExcerpt($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeExcerpt($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addExcerpt($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeExcerpt($this);
        }

        return $this;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function setCreatorId(?int $creatorId): static
    {
        $this->creatorId = $creatorId;

        return $this;
    }
    
}
