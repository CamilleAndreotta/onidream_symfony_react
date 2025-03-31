<?php

namespace App\Action\Excerpts;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ShowExcerpt extends AbstractActions
{   
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer,
        private VerifyUserPermissionsService $verifyUserPermissions
    )
    {
        parent::__construct($serializer, $em);
    }

    public function show(null|Excerpts $object, Users $user): string
    {   
        if(!$object){
            return new JsonResponse("L'auteur n'existe pas en base de données.", 500, [], true);
        }

        try{
            $this->verifyUserPermissions->isLinkedWithObject($object, $user);
        } catch (AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }
        
        $excerpt = $this->em->getRepository(Excerpts::class)->find($object);
        return $this->serialize($excerpt, EntityTypeUtils::EXCERPT);
    }
}