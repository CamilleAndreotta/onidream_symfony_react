<?php

namespace App\Action\Books;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Books;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Exception\EntityIsLinkedException;
use App\Repository\BooksRepository;
use App\Service\Security\VerifyUserPermissionsService;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteBook extends AbstractActions
{   

    public function __construct(
        private BooksRepository $editorsRepository,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){}

    public function delete(null|Books $book, Users $user)
    {   
        try{
            if($book === null){
                throw new EntityExistException("Le livre n'existe pas en base de donnée.");
            }

            $this->verifyUserPermissions->isCreatorOfObject($book, $user);

            if(!$book->getExcerpts()->isEmpty()){
                throw new EntityIsLinkedException("Vous ne pouvez pas supprimer le livre lié à des extraits.");
            };

        } catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        } catch(EntityIsLinkedException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }
        
        $this->editorsRepository->remove($book);

        return 'Suppression effectuée avec succès.';

        
    }
}