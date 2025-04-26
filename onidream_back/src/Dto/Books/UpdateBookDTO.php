<?php 

namespace App\Dto\Books;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateBookDTO
{   

    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    public string $summary;

    #[Assert\Length(min: 13, max: 13)]
    public string $isbn;

    #[Assert\Date]
    public ?string $publishedAt = null;
}