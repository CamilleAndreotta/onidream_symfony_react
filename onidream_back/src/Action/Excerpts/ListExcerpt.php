<?php 

namespace App\Action\Excerpts;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use App\Utils\UserRoleTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ListExcerpt extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){
        parent::__construct($serializer);
    }

    public function list(Users $user): string|JsonResponse
    {   
        try{
           $this->verifyUserPermissions->isAdmin($user);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }
        
        $excerpts = $this->em->getRepository(Excerpts::class)->findAll();
        return $this->serialize($excerpts, EntityTypeUtils::EXCERPT);
    }
}