<?php

namespace App\Controller\Api;

use App\Action\Auth\LoginUser;
use App\Action\Auth\RegisterUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

}
