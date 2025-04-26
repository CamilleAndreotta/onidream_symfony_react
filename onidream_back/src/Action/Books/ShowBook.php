<?php

namespace App\Action\Books;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Books;
use App\Entity\Users;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ShowBook extends AbstractActions
{   
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer, 
        private VerifyUserPermissionsService $verifyUserPermissions
    )
    {
        parent::__construct($serializer, $em);
    }

    public function show(null|Books $object, Users $user): string
    {   
        if(!$object){
            return new JsonResponse("Le livre n'existe pas en base de données.", 500, [], true);
        }

        try{

            $this->verifyUserPermissions->isLinkedWithObject($object, $user);

        } catch (AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }

        $book = $this->em->getRepository(Books::class)->find($object);
        return $this->serialize($book, EntityTypeUtils::BOOK);
    }
}