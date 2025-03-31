<?php

namespace App\Action\Categories;

use App\Action\AbstractActions;
use App\Dto\Categories\CreateCategoryDTO;
use App\Entity\Categories;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\CategoriesRepository;
use App\Utils\EntityTypeUtils;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateCategory extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CategoriesRepository $categoriesRepository
    ){
        parent::__construct($serializer);
    }

    public function create(string $json, Users $user): JsonResponse|string
    {  
        try{
            
            $dataFromJson = json_decode($json, true);

            if(isset($dataFromJson['category'])){
                $jsonCategory = json_encode($dataFromJson['category']);
            };

            $createCategoryDTO = $this->deserialize($jsonCategory, CreateCategoryDTO::class, 'json');

            $errors = $this->validator->validate($createCategoryDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }
            
        } catch(Exception $e) {
            return new JsonResponse("Le format du Json n'est pas valide.", 500, [], true);
        } 

        $category = new Categories();
        $category->setName($createCategoryDTO->name);
 
        $categorySaved = $this->categoriesRepository->add($category, $user);
        return $this->serialize($categorySaved, EntityTypeUtils::CATEGORY);
    }


}