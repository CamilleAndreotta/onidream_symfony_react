<?php

namespace App\Service\Security;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Books;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Utils\UserRoleTypeUtils;
use Doctrine\ORM\EntityManagerInterface;

class VerifyUserPermissionsService
{   
    public function __construct(
        private EntityManagerInterface $em,
    ){}

    public function isAdmin($user)
    {
        if(!(in_array(UserRoleTypeUtils::ADMIN, $user->getRoles())))
        {
            throw new AccessDeniedException("Vous n'avez pas le droit de consulter cette ressource.");
        };
    }

    public function isCreatorOfObject(Books|Excerpts $object, Users $user){
        if($object->getCreatorId() !== $user->getId()){
            throw new AccessDeniedException("Vous n'avez pas les droits de modification sur ce livre.");
        }
    }

    public function isLinkedWithObject(Books|Excerpts $object, Users $user){
        if($object instanceof Books){
            $isObjectIsLinkWithUser = $this->em->getRepository(Books::class)->isBookIsLinkedWithUser($object, $user);
        };

        if($object instanceof Excerpts){
            $isObjectIsLinkWithUser = $this->em->getRepository(Excerpts::class)->isExcerptIsLinkedWithUser($object, $user);
        }

        if(!$isObjectIsLinkWithUser){
            throw new AccessDeniedException("Vous n'avez pas les droits pour consulter cette ressource");
        }
    }

    public function isUserSameAsUserConnected(Users $user, Users $userConnected){
        if(!($user === $userConnected)){
            throw new AccessDeniedException("Vous n'avez pas les droits pour consulter cette ressource.");
        }
    }
}