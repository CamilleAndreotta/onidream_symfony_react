<?php

namespace App\Entity;

use App\Repository\ExcerptsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExcerptsRepository::class)]
class Excerpts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $bookStartedOn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $bookEndedOn = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $bookPage = null;

    #[ORM\ManyToOne(inversedBy: 'excerpts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Books $books = null;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'excerpts')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
    
}
