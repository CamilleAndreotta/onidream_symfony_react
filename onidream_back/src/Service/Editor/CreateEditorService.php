<?php

namespace App\Service\Editor;

use App\Entity\Editors;
use App\Exception\EntityExistException;
use App\Repository\EditorsRepository;
use App\Service\AbstractJsonService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateEditorService extends AbstractJsonService
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EditorsRepository $editorsRepository,
    ){
        parent::__construct($serializer);
    }

    public function create(string $json): JsonResponse|string
    {  
        try{
            $editor = $this->deserializeJsonDataToCreateObject($json, Editors::class);
            
            $isEditorExist = $this->editorsRepository->findByName($editor);

            if($isEditorExist !== null){
                throw new EntityExistException("L'éditeur existe déjà en base de donnée.");
            }
            
        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), '500', [], true);
        } catch(Exception $e) {
            return new JsonResponse("Le format du Json n'est pas valide.", '500', [], true);
        } 

        $errors = $this->validator->validate($editor);
        if(count($errors)>0){
            return new JsonResponse($errors, '500', [], true);
        }
        
        $editorSaved = $this->editorsRepository->add($editor);
        return $this->serializeObjectToJson($editorSaved);
    }


}