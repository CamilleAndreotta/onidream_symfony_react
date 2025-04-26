<?php

namespace App\Action\Categories;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Dto\Categories\UpdateCategoryDTO;
use App\Entity\Categories;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\CategoriesRepository;
use App\Utils\EntityTypeUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditCategory extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private CategoriesRepository $categoriesRepository,
        private ValidatorInterface $validator,
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Categories $category, Users $user)
    {   
        try{

            if($category === null){
                throw new EntityExistException("La catégorie n'existe pas en base de données.");
            }

            if($category->getUsers() !== $user){
                throw new AccessDeniedException("Vous n'avez pas les droits d'édition sur cette ressource.");
            }
            
            $dataFromJson = json_decode($json, true);
            if(isset($dataFromJson['category'])){
                $jsonCategory = json_encode($dataFromJson['category']);
            }

            $updateCategoyDTO = $this->deserialize($jsonCategory, UpdateCategoryDTO::class, 'json');
            $errors = $this->validator->validate($updateCategoyDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }
              
        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }

        $categoryToUpdate = $this->deserialize($jsonCategory, Categories::class, $category);
        $categoryUpdated = $this->categoriesRepository->update($categoryToUpdate);
        return $this->serialize($categoryUpdated, EntityTypeUtils::CATEGORY);
    }
}