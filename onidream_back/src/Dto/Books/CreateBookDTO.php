<?php 

namespace App\Dto\Books;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBookDTO
{   
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    public string $summary;

    #[Assert\NotBlank]
    #[Assert\Length(min: 13, max: 13)]
    public string $isbn;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $publishedAt;
}