<?php

namespace App\Action\Authors;

use App\Action\AbstractActions;
use App\Entity\Authors;
use App\Entity\Users;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ListAuthor extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    ){
        parent::__construct($serializer);
    }

    public function list(Users $user): string
    {   
        $authors = $this->em->getRepository(Authors::class)->findAuthorsByUser($user);
        return $this->serialize($authors, EntityTypeUtils::AUTHOR);
    }

}