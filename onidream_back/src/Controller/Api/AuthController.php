<?php

namespace App\Controller\Api;

use App\Action\Auth\LoginUser;
use App\Action\Auth\RegisterUser;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{   
    public function __construct(
        private RegisterUser $registerUser,
        private LoginUser $loginUser
    ){}


    #[Route('/api/register', name: 'app_api_register', methods:["POST"])]
    public function register(Request $request): JsonResponse
    {   
        $json = $request->getContent();           
        return new JsonResponse($this->registerUser->create($json), 200, [], true);
    }

    #[Route('/api/login', name: 'app_api_login', methods:["POST"])]
    public function login(Request $request): JsonResponse
    {   
        $json = $request->getContent();
        return new JsonResponse($this->loginUser->login($json), 200, [], true);
    }

    #[Route('/api/user', name: 'app_api_check', methods:["GET"])]
    public function getConnectedUser(Security $security): JsonResponse 
    {   
        $user = $security->getUser();
        if($user instanceof Users) {
            $id = $user->getId();
            $firstName = $user->getFirstname();
            $lastName = $user->getLastname();
            $email = $user->getEmail();
        }

        return $this->json([
           'id' => $id,
           'email' => $email,
           'firstname' => $firstName,
           'lastname' => $lastName
        ]);
    }

}
