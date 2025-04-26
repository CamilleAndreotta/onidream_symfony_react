<?php

namespace App\Dto\Excerpts;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateExcerptDTO
{

    #[Assert\NotBlank]
    public string $text;

    #[Assert\Date]
    public ?string $bookStartedOn = null;

    #[Assert\Date]
    public ?string $bookEndedOn = null;

    public string $bookPage;

    

}