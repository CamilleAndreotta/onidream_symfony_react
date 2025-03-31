<?php

namespace App\Dto\Excerpts;

use Symfony\Component\Validator\Constraints as Assert;

class CreateExcerptDTO
{

    #[Assert\NotBlank]
    public string $text;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $bookStartedOn;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $bookEndedOn;

    #[Assert\NotBlank]
    public string $bookPage;

    

}