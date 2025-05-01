<?php

namespace App\Action\Auth;

use App\Action\AbstractActions;
use App\Dto\Users\CreateUserDTO;
use App\Dto\Users\LoginUserDTO;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\UsersRepository;
use App\Utils\EntityTypeUtils;
use App\Utils\UserRoleTypeUtils;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginUser extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private UsersRepository $usersRepository,
        private UserPasswordHasherInterface $hasher,
        private JWTTokenManagerInterface $jwtManager
    ){
        parent::__construct($serializer);
    }

    public function login(string $json): JsonResponse|string
    {  

        try{
            $dataFromJson = json_decode($json, true);
        
        if(isset($dataFromJson['user'])){
            $jsonUser = json_encode($dataFromJson['user']);
        }

        $loginUserDTO = $this->deserialize($jsonUser, LoginUserDTO::class, 'json');
        $errors = $this->validator->validate($loginUserDTO); 

        if(count($errors) > 0){
            return new JsonResponse($errors, 500, [], true);
        }

        $isUserExist = $this->usersRepository->findByEmail($loginUserDTO->email);

        if($isUserExist === null){
            throw new EntityExistException("L'identifiant ou le mot de passe ne semble pas correspondre.");
        }

        if(!$this->hasher->isPasswordValid($isUserExist, $loginUserDTO->password)){
            throw new EntityExistException("L'identifiant ou le mot de passe ne semble pas correspondre.");
        };

        } catch (EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }

        $token = ["token" =>$this->jwtManager->create($isUserExist)];

        return $this->serializeToken($token);
    }

}