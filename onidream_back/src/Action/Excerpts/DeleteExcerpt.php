<?php

namespace App\Action\Excerpts;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\ExcerptsRepository;
use App\Service\Security\VerifyUserPermissionsService;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteExcerpt extends AbstractActions
{   

    public function __construct(
        private ExcerptsRepository $excerptsRepository,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){}

    public function delete(null|Excerpts $excerpt, Users $user)
    {   
        try{
            if($excerpt === null){
                throw new EntityExistException("L'extrait n'existe pas en base de donnée.");
            }

            $this->verifyUserPermissions->isCreatorOfObject($excerpt, $user);

        } catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }
        
        $this->excerptsRepository->remove($excerpt);

        return 'Suppression effectuée avec succès.';

        
    }
}