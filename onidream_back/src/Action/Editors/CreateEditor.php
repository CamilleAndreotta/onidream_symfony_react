<?php

namespace App\Action\Editors;

use App\Action\AbstractActions;
use App\Dto\Editors\CreateEditorDTO;
use App\Entity\Editors;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\EditorsRepository;
use App\Utils\EntityTypeUtils;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateEditor extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EditorsRepository $editorsRepository,
    ){
        parent::__construct($serializer);
    }

    public function create(string $json, Users $user): JsonResponse|string
    {  
        try{

            $dataFromJson = json_decode($json, true);
            
            if(isset($dataFromJson['editor'])){
                $jsonEditor = json_encode($dataFromJson['editor']);
            };

            $createEditorDTO = $this->deserialize($jsonEditor, CreateEditorDTO::class, 'json');
            $errors = $this->validator->validate($createEditorDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }

        } catch(Exception $e) {
            return new JsonResponse("Le format du Json n'est pas valide.", '500', [], true);
        } 

        $editor = new Editors();
        $editor->setName($createEditorDTO->name);
        $editorSaved = $this->editorsRepository->add($editor, $user);
        return $this->serialize($editorSaved, EntityTypeUtils::EDITOR);
    }


}