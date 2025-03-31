<?php 

namespace App\Dto\Users;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO
{   
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $lastname;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 12)]
    #[Assert\Regex(
        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/", 
        "Le mote de passe doit contenir au minimum 12 caractères dont une minuscule, une majuscule, un chiffre et un caractère spécial. ")]
    public string $password;

}