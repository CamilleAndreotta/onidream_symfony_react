<?php

namespace App\Action\Editors;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Editors;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Exception\EntityIsLinkedException;
use App\Repository\EditorsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteEditor extends AbstractActions
{   

    public function __construct(
        private EditorsRepository $editorsRepository
    ){}

    public function delete(null|Editors $editor, Users $user)
    {   
        try{
            if($editor === null){
                throw new EntityExistException("L'éditeur n'existe pas en base de donnée.");
            }
            if($editor->getUsers() !== $user){
                throw new AccessDeniedException("Vous n'avez pas les droits de supprimer cette ressource.");
            }
            if(!$editor->getBooks()->isEmpty()){
                throw new EntityIsLinkedException("Vous ne pouvez pas modifier un éditeur lié à un ou plusieurs livres.");
            }


        } catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch (AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        } catch(EntityIsLinkedException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }
        
        $this->editorsRepository->remove($editor);

        return 'Suppression effectuée avec succès.';

        
    }
}