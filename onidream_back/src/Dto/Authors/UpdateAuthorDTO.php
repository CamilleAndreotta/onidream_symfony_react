<?php 

namespace App\Dto\Authors;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateAuthorDTO
{   
    #[Assert\Length(min: 1, max: 255)]
    public string $firstname;

    #[Assert\Length(min: 1, max: 255)]
    public string $lastname;

    #[Assert\Date]
    public ?string $birthDate = null;

    public ?string $biography = null;
}