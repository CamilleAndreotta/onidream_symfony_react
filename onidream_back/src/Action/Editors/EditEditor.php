<?php

namespace App\Action\Editors;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Dto\Editors\UpdateEditorDTO;
use App\Entity\Editors;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\EditorsRepository;
use App\Utils\EntityTypeUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditEditor extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private EditorsRepository $editorsRepository,
        private ValidatorInterface $validator,
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Editors $editor, Users $user)
    {  
        try{

            if($editor === null){
                throw new EntityExistException("L'éditeur n'existe pas en base de données.");
            }

            if($editor->getUsers() !== $user){
                throw new AccessDeniedException("Vous n'avez pas les droits d'édition sur cette ressource.");
            }

            $dataFromJson = json_decode($json, true);
            if(isset($dataFromJson['editor'])){
                $jsonEditor = json_encode($dataFromJson['editor']);
            }

            $updateEditorDTO = $this->deserialize($jsonEditor, UpdateEditorDTO::class, 'json');
            $errors = $this->validator->validate($updateEditorDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }
              
        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }

        $editorToUpdate = $this->deserialize($jsonEditor, Editors::class, $editor);
        $editorUpdated = $this->editorsRepository->update($editorToUpdate);
        return $this->serialize($editorUpdated, EntityTypeUtils::EDITOR);
    }
}