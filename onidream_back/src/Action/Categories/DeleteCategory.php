<?php

namespace App\Action\Categories;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Entity\Categories;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteCategory extends AbstractActions
{   

    public function __construct(
        private CategoriesRepository $categoriesRepository
    ){}

    public function delete(null|Categories $category, Users $user)
    {   
        try{
            if($category === null){
                throw new EntityExistException("La catégorie n'existe pas en base de donnée.");
            }

            if($category->getUsers() !== $user){
                throw new AccessDeniedException("Vous n'avez pas les droits de supprimer cette ressource.");
            }

        } catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch (AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }
        
        $this->categoriesRepository->remove($category);

        return 'Suppression effectuée avec succès.';

        
    }
}