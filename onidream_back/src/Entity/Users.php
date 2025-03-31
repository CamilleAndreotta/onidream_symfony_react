<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( ["user:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups( ["user:read"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups( ["user:read"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups( ["user:read"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups( ["user:read"])]
    private ?string $password = null;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    /**
     * @var Collection<int, Books>
     */
    #[ORM\ManyToMany(targetEntity: Books::class, inversedBy: 'users')]
    private Collection $books;

    /**
     * @var Collection<int, Editors>
     */
    #[ORM\OneToMany(targetEntity: Editors::class, mappedBy: 'users')]
    private Collection $editors;

    /**
     * @var Collection<int, Authors>
     */
    #[ORM\OneToMany(targetEntity: Authors::class, mappedBy: 'users')]
    private Collection $authors;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\OneToMany(targetEntity: Categories::class, mappedBy: 'users')]
    private Collection $categories;

    /**
     * @var Collection<int, Excerpts>
     */
    #[ORM\ManyToMany(targetEntity: Excerpts::class, inversedBy: 'users')]
    private Collection $excerpts;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->editors = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->excerpts = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Books $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Books $book): static
    {
        $this->books->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, Editors>
     */
    public function getEditors(): Collection
    {
        return $this->editors;
    }

    public function addEditor(Editors $editor): static
    {
        if (!$this->editors->contains($editor)) {
            $this->editors->add($editor);
            $editor->setUsers($this);
        }

        return $this;
    }

    public function removeEditor(Editors $editor): static
    {
        if ($this->editors->removeElement($editor)) {
            // set the owning side to null (unless already changed)
            if ($editor->getUsers() === $this) {
                $editor->setUsers(null);
            }
        }

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
            $author->setUsers($this);
        }

        return $this;
    }

    public function removeAuthor(Authors $author): static
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getUsers() === $this) {
                $author->setUsers(null);
            }
        }

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
            $category->setUsers($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUsers() === $this) {
                $category->setUsers(null);
            }
        }

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

    public function eraseCredentials(): void
    {
        
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
