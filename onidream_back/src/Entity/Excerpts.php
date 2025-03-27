<?php

namespace App\Entity;

use App\Repository\ExcerptsRepository;
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
    
}
