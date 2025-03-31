<?php 

namespace App\Dto\Authors;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAuthorDTO
{   
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $lastname;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $birthDate;

    public ?string $biography;
}