<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publishedAt = null;

    /**
     * @var Collection<int, Autors>
     */
    #[ORM\ManyToMany(targetEntity: Autors::class, mappedBy: 'book')]
    private Collection $autors;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?editors $editor = null;

    /**
     * @var Collection<int, Excerpts>
     */
    #[ORM\OneToMany(targetEntity: Excerpts::class, mappedBy: 'books')]
    private Collection $excerpts;

    public function __construct()
    {
        $this->autors = new ArrayCollection();
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
     * @return Collection<int, Autors>
     */
    public function getAutors(): Collection
    {
        return $this->autors;
    }

    public function addAutor(Autors $autor): static
    {
        if (!$this->autors->contains($autor)) {
            $this->autors->add($autor);
            $autor->addBook($this);
        }

        return $this;
    }

    public function removeAutor(Autors $autor): static
    {
        if ($this->autors->removeElement($autor)) {
            $autor->removeBook($this);
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
}
