<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( ["book:read", "editor:read", "excerpt:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups( ["book:read", "category:read", "author:read", "editor:read", "category:show", "excerpt:read"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups( ["book:read", "category:read", "author:read", "editor:read", "category:show", "excerpt:read"])]
    private ?string $summary = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups( ["book:read", "category:read", "author:read", "editor:read", "category:show", "excerpt:read"])]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups( ["book:read", "category:read", "author:read", "editor:read", "category:show", "excerpt:read"])]
    private ?\DateTimeInterface $publishedAt = null;

    /**
     * @var Collection<int, Authors>
     */
    #[ORM\ManyToMany(targetEntity: Authors::class, mappedBy: 'book')]
    #[Groups( ["book:read", "category:read", "editor:read", "category:show", "excerpt:read"])]
    private Collection $authors;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups( ["book:read", "author:read", "category:show", "excerpt:read"])]
    private ?Editors $editor = null;

    /**
     * @var Collection<int, Excerpts>
     */
    #[ORM\OneToMany(targetEntity: Excerpts::class, mappedBy: 'books')]
    #[Groups( ["book:read", "category:read", "author:read", "editor:read"])]
    private Collection $excerpts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'books')]
    private Collection $users;

    #[ORM\Column(nullable: true)]
    private ?int $creatorId = null;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->excerpts = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection<int, Authors>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Authors $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Authors $author): static
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    public function getEditor(): ?editors
    {
        return $this->editor;
    }

    public function setEditor(?editors $editor): static
    {
        $this->editor = $editor;

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
            $excerpt->setBooks($this);
        }

        return $this;
    }

    public function removeExcerpt(Excerpts $excerpt): static
    {
        if ($this->excerpts->removeElement($excerpt)) {
            // set the owning side to null (unless already changed)
            if ($excerpt->getBooks() === $this) {
                $excerpt->setBooks(null);
            }
        }

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
            $user->addBook($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeBook($this);
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
