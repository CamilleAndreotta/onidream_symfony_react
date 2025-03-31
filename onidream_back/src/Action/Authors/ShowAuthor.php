<?php

namespace App\Action\Authors;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Authors;
use App\Entity\Users;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ShowAuthor extends AbstractActions
{   
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    )
    {
        parent::__construct($serializer, $em);
    }

    public function show(null|Authors $object, Users $user): string
    {   
        if(!$object){
            return new JsonResponse("L'auteur n'existe pas en base de données.", 500, [], true);
        }
        
        try{
            if($object->getUsers() !== $user){
                Throw new AccessDeniedException("Vous n'avez pas les droit d'accès à cette ressource.");
            }
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }

        $author = $this->em->getRepository(Authors::class)->find($object->getId());
        return $this->serialize($author, EntityTypeUtils::AUTHOR);
    }
}