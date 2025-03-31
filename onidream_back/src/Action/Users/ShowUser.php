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

class ShowUser extends AbstractActions
{   
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer,
        private VerifyUserPermissionsService $verifyUserPermissions
    )
    {
        parent::__construct($serializer, $em);
    }

    public function show(null|Users $object, Users $connectedUser): string
    {   
        if(!$object){
            return new JsonResponse("L'utilisateur n'existe pas en base de données.", 500, [], true);
        }

        try{
            $this->verifyUserPermissions->isUserSameAsUserConnected($object, $connectedUser);
        }catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }

        $user = $this->em->getRepository(Users::class)->find($object->getId());

        return $this->serialize($user, EntityTypeUtils::USER);
    }
}