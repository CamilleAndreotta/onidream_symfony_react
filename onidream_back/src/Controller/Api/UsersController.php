<?php

namespace App\Controller\Api;

use App\Action\Users\CreateUser;
use App\Action\Users\DeleteUser;
use App\Action\Users\EditUser;
use App\Action\Users\ListUser;
use App\Action\Users\ShowUser;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UsersController extends AbstractController
{   

    public function __construct(
        private ListUser $listUsers,
        private ShowUser $showUser,
        private CreateUser $createUser,
        private EditUser $editUser,
        private DeleteUser $deleteUser
    ){}


    #[Route('/api/users', name: 'app_api_users', methods:'GET')]
    public function index(Security $security): JsonResponse
    {   
        $user = $security->getUser();   
        return new JsonResponse($this->listUsers->list($user), '200', [], true);
    }

    #[Route('/api/users/{user}', name: 'app_api_user', methods:['GET'])]
    public function findOne(Users|null $user, Security $security): JsonResponse
    {   
        $connectedUser = $security->getUser();
        return new JsonResponse($this->showUser->show($user, $connectedUser), '200', [], true);
    }

    #[Route('/api/users', name: 'app_api_user_create', methods:['POST'])]
    public function create(Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->createUser->create($json, $user), '200', [], true);
    }

    #[Route('/api/users/{user}', name: 'app_api_user_update', methods:['PUT', 'PATCH'])]
    public function update(null|Users $user, Request $request, Security $security): JsonResponse
    {       
        $json = $request->getContent();
        $connectedUser = $security->getUser();
        return new JsonResponse($this->editUser->update($json, $user, $connectedUser), '200', [], true);
    }

    #[Route('/api/users/{user}', name: 'app_api_user_delete', methods:['DELETE'])]
    public function delete(null|Users $user, Security $security): JsonResponse
    {   
        $connectedUser = $security->getUser();
        return new JsonResponse($this->deleteUser->delete($user, $connectedUser), '200', [], true);
    }

}
