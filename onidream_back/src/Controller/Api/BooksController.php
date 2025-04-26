<?php

namespace App\Controller\Api;

use App\Action\Books\CreateBook;
use App\Action\Books\DeleteBook;
use App\Action\Books\EditBook;
use App\Action\Books\ListBook;
use App\Action\Books\ListBookByUser;
use App\Action\Books\ShowBook;
use App\Entity\Books;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BooksController extends AbstractController
{   
    public function __construct(
        private ListBook $listBooks,
        private ListBookByUser $listBookByUser,
        private ShowBook $showBook,
        private CreateBook $createBook,
        private EditBook $editBook,
        private DeleteBook $deleteBook
    ){}
    
    #[Route('/api/books', name: 'app_api_books', methods:['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse($this->listBooks->list(), 200, [], true);
    }

    #[Route('/api/books/user', name: 'app_api_books_user', methods:['GET'])]
    public function indexBooksByUser(Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->listBookByUser->list($user), 200, [], true);
    }

    #[Route('/api/books/{book}', name: 'app_api_book', methods:['GET'])]
    public function findOne(null|Books $book, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->showBook->show($book, $user), 200, [], true);
    }

    #[Route('/api/books', name: 'app_api_book_create', methods:['POST'])]
    public function create(Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->createBook->create($json, $user), 200, [], true);
    }

    #[Route('/api/books/{book}', name: 'app_api_book_update', methods:['PUT', 'PATCH'])]
    public function update(null|Books $book, Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->editBook->update($json, $book, $user), 200, [], true);
    }

    #[Route('/api/books/{book}', name: 'app_api_book_delete', methods:['DELETE'])]
    public function delete(null|Books $book, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->deleteBook->delete($book, $user), 200, [], true);
    }

}
