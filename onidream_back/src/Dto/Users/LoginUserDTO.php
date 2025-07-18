<?php 

namespace App\Dto\Users;

use Symfony\Component\Validator\Constraints as Assert;

class LoginUserDTO
{   
    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}