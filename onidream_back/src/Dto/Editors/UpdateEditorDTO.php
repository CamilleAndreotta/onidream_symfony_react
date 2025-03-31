<?php 

namespace App\Dto\Editors;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateEditorDTO
{   
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;
}