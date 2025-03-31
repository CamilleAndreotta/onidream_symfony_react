<?php 

namespace App\Dto\Categories;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryDTO
{   
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;
}