<?php

namespace App\Service\Editor;

use App\Entity\Editors;
use App\Exception\EntityExistException;
use App\Repository\EditorsRepository;
use App\Service\AbstractJsonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteEditorService extends AbstractJsonService
{   

    public function __construct(
        private EditorsRepository $editorsRepository
    ){}

    public function delete(null|Editors $editor)
    {   
        try{
            if($editor === null){
                throw new EntityExistException("L'éditeur n'existe déjà en base de donnée.");
            }
        } catch (EntityExistException $e)
        {
            return new JsonResponse($e->getMessage(), '500', [], true);
        }
        
        $this->editorsRepository->remove($editor);

        return 'Suppression effectuée avec succès.';

        
    }
}