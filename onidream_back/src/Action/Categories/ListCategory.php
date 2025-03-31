<?php

namespace App\Action\Categories;

use App\Action\AbstractActions;
use App\Entity\Categories;
use App\Entity\Users;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ListCategory extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    ){
        parent::__construct($serializer);
    }

    public function list(Users $user): string
    {
        $categories = $this->em->getRepository(Categories::class)->findCategoriesByUser($user);
        return $this->serialize($categories, EntityTypeUtils::CATEGORY);
    }

}