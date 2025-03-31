<?php

namespace App\Controller\Api;

use App\Action\Authors\CreateAuthor;
use App\Entity\Authors;
use App\Action\Authors\DeleteAuthor;
use App\Action\Authors\EditAuthor;
use App\Action\Authors\ListAuthor;
use App\Action\Authors\ShowAuthor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorsController extends AbstractController
{   

    public function __construct(
        private ListAuthor $listAuthors,
        private ShowAuthor $showAuthor,
        private CreateAuthor $createAuthor,
        private EditAuthor $editAuthor,
        private DeleteAuthor $deleteAuthor
    ){}


    #[Route('/api/authors', name: 'app_api_authors', methods:'GET')]
    public function index(Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->listAuthors->list($user), 200, [], true);
    }

    #[Route('/api/authors/{author}', name: 'app_api_author', methods:['GET'])]
    public function findOne(Authors|null $author, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->showAuthor->show($author, $user), 200, [], true);
    }

    #[Route('/api/authors', name: 'app_api_author_create', methods:['POST'])]
    public function create(Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->createAuthor->create($json, $user), 200, [], true);
    }

    #[Route('/api/authors/{author}', name: 'app_api_author_update', methods:['PUT', 'PATCH'])]
    public function update(null|Authors $author, Request $request, Security $security): JsonResponse
    {       
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->editAuthor->update($json, $author, $user), 200, [], true);
    }

    #[Route('/api/authors/{author}', name: 'app_api_author_delete', methods:['DELETE'])]
    public function delete(null|Authors $author, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->deleteAuthor->delete($author, $user), 200, [], true);
    }

}
