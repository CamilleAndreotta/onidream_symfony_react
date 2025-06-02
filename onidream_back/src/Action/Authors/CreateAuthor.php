<?php

namespace App\Action\Authors;

use App\Action\AbstractActions;
use App\Dto\Authors\CreateAuthorDTO;
use App\Entity\Authors;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\AuthorsRepository;
use App\Utils\EntityTypeUtils;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAuthor extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private AuthorsRepository $authorsRepository
    ){
        parent::__construct($serializer);
    }

    public function create(string $json, Users $user): JsonResponse|string
    {  
        try{
            
            $dataFromJson = json_decode($json, true);

            if(isset($dataFromJson['author'])){
                $jsonAuthor = json_encode($dataFromJson['author']);
            };

            $createAuthorDTO = $this->deserialize($jsonAuthor, CreateAuthorDTO::class, 'json');
            $errors = $this->validator->validate($createAuthorDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }
            
        } catch(Exception $e) {
            return new JsonResponse("Le format du Json n'est pas valide.", 500, [], true);
        } 
        
        $birthDate = new DateTime($createAuthorDTO->birthDate);
        $deathDate = new DateTime($createAuthorDTO->deathDate);
        
        $author = new Authors();
        $author->setFirstname($createAuthorDTO->firstname);
        $author->setLastname($createAuthorDTO->lastname);
        $author->setBirthDate($birthDate);
        $author->setDeathDate($deathDate);
        $author->setBiography($createAuthorDTO->biography);
        
        $authorSaved = $this->authorsRepository->add($author, $user);

        return $this->serialize($authorSaved, EntityTypeUtils::AUTHOR);
    }


}