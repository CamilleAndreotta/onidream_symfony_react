<?php

namespace App\Action\Auth;

use App\Action\AbstractActions;
use App\Dto\Users\CreateUserDTO;
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

class RegisterUser extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private UsersRepository $usersRepository,
        private UserPasswordHasherInterface $hasher,
        private JWTTokenManagerInterface $jwtManager,
    ){
        parent::__construct($serializer);
    }

    public function create(string $json): JsonResponse|string
    {  
        try{
            
            $dataFromJson = json_decode($json, true);

            if(isset($dataFromJson['user'])){
                $jsonUser = json_encode($dataFromJson['user']);
            };

            $createUserDTO = $this->deserialize($jsonUser, CreateUserDTO::class, 'json');
            $errors = $this->validator->validate($createUserDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }

            $isUserExist = $this->usersRepository->findByEmail($createUserDTO->email);
            if($isUserExist !== null){
                throw new EntityExistException("L'utilisateur existe déjà en base de donnée.");
            }
            
        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } 
            $user = new Users();
            $user->setFirstname($createUserDTO->firstname);
            $user->setLastname($createUserDTO->lastname);
            $user->setEmail(strtolower($createUserDTO->email));
            $user->setPassword($this->hasher->hashPassword($user, $createUserDTO->password));
            $user->setRoles([UserRoleTypeUtils::USER]);
            $userSaved = $this->usersRepository->add($user);

            $token = $this->jwtManager->create($userSaved);
            $user = [
                'user' => $userSaved,
                'token' => $token,
            ];

            return $this->serialize($user, EntityTypeUtils::USER);
    }


}