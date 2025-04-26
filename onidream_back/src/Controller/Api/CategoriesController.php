<?php

namespace App\Controller\Api;

use App\Action\Categories\CreateCategory;
use App\Action\Categories\DeleteCategory;
use App\Action\Categories\EditCategory;
use App\Action\Categories\ListCategory;
use App\Action\Categories\ShowCategory;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoriesController extends AbstractController
{   

    public function __construct(
        private ListCategory $listCategories,
        private ShowCategory $showCategory,
        private CreateCategory $createCategory,
        private EditCategory $editCategory,
        private DeleteCategory $deleteCategory
    ){}


    #[Route('/api/categories', name: 'app_api_categories', methods:'GET')]
    public function index(Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->listCategories->list($user), 200, [], true);
    }

    #[Route('/api/categories/{category}', name: 'app_api_category', methods:['GET'])]
    public function findOne(null|Categories $category, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->showCategory->show($category, $user), 200, [], true);
    }

    #[Route('/api/categories', name: 'app_api_category_create', methods:['POST'])]
    public function create(Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->createCategory->create($json, $user), 200, [], true);
    }

    #[Route('/api/categories/{category}', name: 'app_api_category_update', methods:['PUT', 'PATCH'])]
    public function update(null|Categories $category, Request $request, Security $security): JsonResponse
    {       
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->editCategory->update($json, $category, $user), 200, [], true);
    }

    #[Route('/api/categories/{category}', name: 'app_api_category_delete', methods:['DELETE'])]
    public function delete(null|Categories $category, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->deleteCategory->delete($category, $user), 200, [], true);
    }

}
