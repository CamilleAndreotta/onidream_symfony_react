<?php 

namespace App\Action\Excerpts;

use App\Action\AbstractActions;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ListExcerptByUser extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    ){
        parent::__construct($serializer);
    }

    public function list(Users $user): string
    {   
        $excerpts = $this->em->getRepository(Excerpts::class)->findExcerptsByUser($user);
        return $this->serialize($excerpts, EntityTypeUtils::EXCERPT);
    }
}