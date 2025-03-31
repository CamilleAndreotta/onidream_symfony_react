<?php

namespace App\Action\Users;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Users;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ListUser extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){
        parent::__construct($serializer);
    }

    public function list(Users $user): string
    {   
        try{
            $this->verifyUserPermissions->isAdmin($user);
         } catch(AccessDeniedException $e){
             return new JsonResponse($e->getMessage(), 403, [], true);
         }
         
        $users = $this->em->getRepository(Users::class)->findAll();
        return $this->serialize($users, EntityTypeUtils::USER);
    }

}