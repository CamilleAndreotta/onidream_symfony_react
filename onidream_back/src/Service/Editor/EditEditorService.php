<?php

namespace App\Service\Editor;

use App\Entity\Editors;
use App\Exception\EntityExistException;
use App\Repository\EditorsRepository;
use App\Service\AbstractJsonService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditEditorService extends AbstractJsonService
{
    public function __construct(
        SerializerInterface $serializer,
        private EditorsRepository $editorsRepository,
        private ValidatorInterface $validator,
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Editors $editor)
    {  
        try{
            if($editor === null){
                throw new EntityExistException("L'éditeur n'existe pas en base de données.");
            }    

            $editorToUpdate = $this->deserializeJsonDataToUpdateObject($json, Editors::class, $editor);
              
        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), '500', [], true);
        }

        $errors = $this->validator->validate($editor);
        if(count($errors)>0){
            return new JsonResponse($errors, '500', [], true);
        }

        $editorUpdated = $this->editorsRepository->update($editorToUpdate);
        return $this->serializeObjectToJson($editorUpdated);
    }
}