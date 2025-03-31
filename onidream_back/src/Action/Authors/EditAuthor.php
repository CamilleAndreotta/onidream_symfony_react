<?php

namespace App\Action\Authors;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Dto\Authors\UpdateAuthorDTO;
use App\Entity\Authors;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\AuthorsRepository;
use App\Service\Data\ChangeDateFormatService;
use App\Utils\EntityTypeUtils;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditAuthor extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private AuthorsRepository $authorsRepository,
        private ValidatorInterface $validator,
        private ChangeDateFormatService $changeDateFormat
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Authors $author, Users $user)
    {   
        try{

            if($author === null){
                throw new EntityExistException("L'auteur n'existe pas en base de données.");
            }

            if($author->getUsers() !== $user){
                throw new AccessDeniedException("Vous n'avez pas les droits d'édition sur cette ressource.");
            }

            $dataFromJson = json_decode($json, true);
            if(isset($dataFromJson['author'])){
                $jsonAuthor = json_encode($dataFromJson['author']);
            }
            
            $updateAuthorDTO= $this->deserialize($jsonAuthor, UpdateAuthorDTO::class, 'json');

            $errors = $this->validator->validate($updateAuthorDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            } 
              

        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }

        $authorToUpdate = $this->deserialize($jsonAuthor, Authors::class, $author);

    
        if($updateAuthorDTO->birthDate){
            $birthDate = new DateTime($updateAuthorDTO->birthDate);
            $authorToUpdate->setBirthDate($birthDate);
        }

        $authorUpdated = $this->authorsRepository->update($authorToUpdate);
        return $this->serialize($authorUpdated, EntityTypeUtils::AUTHOR);
    }
}