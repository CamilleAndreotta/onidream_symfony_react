<?php

namespace App\Action\Authors;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Authors;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\AuthorsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteAuthor extends AbstractActions
{   

    public function __construct(
        private AuthorsRepository $authorsRepository
    ){}

    public function delete(null|Authors $author, Users $user)
    {   
        try{
            if($author === null){
                throw new EntityExistException("L'auteur n'existe pas en base de donnée.");
            }

            if($author->getUsers() !== $user){
                throw new AccessDeniedException("Vous n'avez pas les droits de supprimer cette ressource.");
            }

        } catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch (AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }
        
        $this->authorsRepository->remove($author);

        return 'Suppression effectuée avec succès.';

        
    }
}