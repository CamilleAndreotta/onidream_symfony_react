<?php

namespace App\Action\Users;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Dto\Users\UpdateUserDTO;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\UsersRepository;
use App\Service\Data\ChangeDateFormatService;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditUser extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private UsersRepository $usersRepository,
        private ValidatorInterface $validator,
        private ChangeDateFormatService $changeDateFormat,
        private UserPasswordHasherInterface $hasher,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Users $user, Users $connectedUser)
    {   
        try{

            if($user === null){
                throw new EntityExistException("L'utilisateur n'existe pas en base de données.");
            }

            try{
                $this->verifyUserPermissions->isUserSameAsUserConnected($user, $connectedUser);
            }catch(AccessDeniedException $e){
                return new JsonResponse($e->getMessage(), 500, [], true);
            }

            $dataFromJson = json_decode($json, true);
            if(isset($dataFromJson['user'])){
                $jsonUser = json_encode($dataFromJson['user']);
            }

            $updateUserDTO = $this->deserialize($jsonUser, UpdateUserDTO::class, 'json');

            $errors = $this->validator->validate($updateUserDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            } 

        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        }

        $userToUpdate = $this->deserialize($jsonUser, Users::class, $user);

        if($updateUserDTO->password){
            $userToUpdate->setPassword($this->hasher->hashPassword($user, $updateUserDTO->password));
        }       

        $userUpdated = $this->usersRepository->update($userToUpdate);
        return $this->serialize($userUpdated, EntityTypeUtils::USER);
    }
}