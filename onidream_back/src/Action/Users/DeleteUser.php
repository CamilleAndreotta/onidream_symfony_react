<?php

namespace App\Action\Users;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Exception\EntityIsLinkedException;
use App\Repository\UsersRepository;
use App\Service\Security\VerifyUserPermissionsService;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteUser extends AbstractActions
{   

    public function __construct(
        private UsersRepository $usersRepository,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){}

    public function delete(null|Users $user, Users $connectedUser)
    {   
        try{
            if($user === null){
                throw new EntityExistException("L'utilisateur n'existe pas en base de donnée.");
            }

            if(!$user->getAuthors()->isEmpty()){
                throw new EntityIsLinkedException("Vous ne pouvez pas supprimer au utilisateur lié à des auteurs.");
            }

            $this->verifyUserPermissions->isUserSameAsUserConnected($user, $connectedUser);

        }catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }catch(EntityIsLinkedException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }
        
        $this->usersRepository->remove($user);

        return 'Suppression effectuée avec succès.';

        
    }
}