<?php

namespace App\Action\Books;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Dto\Books\UpdateBookDTO;
use App\Entity\Books;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\AuthorsRepository;
use App\Repository\BooksRepository;
use App\Repository\EditorsRepository;
use App\Service\Data\ChangeDateFormatService;
use App\Service\Data\OrderArrayDataService;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditBook extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private BooksRepository $booksRepository,
        private ValidatorInterface $validator,
        private ChangeDateFormatService $changeDateFormat,
        private EditorsRepository $editorsRepository,
        private AuthorsRepository $authorsRepository,
        private OrderArrayDataService $orderArrayData,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Books $book, Users $user)
    {  
        try{

            if($book === null){
                throw new EntityExistException("Le livre n'existe pas en base de données.");
            } 

            $this->verifyUserPermissions->isCreatorOfObject($book, $user);
        
            $dataFromJson = json_decode($json, true);

            if(isset($dataFromJson['editor']['id'])){
                $isEditorExist = $this->editorsRepository->find($dataFromJson['editor']['id']);
                if(!$isEditorExist){
                    return new JsonResponse("L'éditeur n'existe pas en base de données.", 500, [], true);
                }
            }

            if(isset($dataFromJson['authors'])){
                $islinkedAuthorsExist = $dataFromJson['authors'];  
            }

            $linkedAuthors = [];
            $unlinkedAuthors = [];
            foreach($islinkedAuthorsExist as $authorInJson){
                $linkedAuthor = $this->authorsRepository->find($authorInJson['id']);
                if(!$linkedAuthor){
                    return new JsonResponse("L'Auteur n'existe pas en base de données.", 500, [], true);
                }

                if($linkedAuthor && !in_array($linkedAuthor, $linkedAuthors)){
                    $linkedAuthors[] = $linkedAuthor;
                } 
            }

            foreach($book->getAuthors() as $currentLinkedAuthor){
                if(!in_array($currentLinkedAuthor, $linkedAuthors)){
                    $unlinkedAuthors[] = $currentLinkedAuthor;
                }
            }

            if(isset($dataFromJson['book'])){
                $jsonBook = json_encode($dataFromJson['book']);
            }

            $updateBookDTO = $this->deserialize($jsonBook, UpdateBookDTO::class, 'json');

            $errors = $this->validator->validate($updateBookDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }
              
        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }
        $bookToUpdate = $this->deserialize($jsonBook, Books::class, $book);

        if($updateBookDTO->publishedAt){
            $publishedAt = new DateTime($updateBookDTO->publishedAt);
            $bookToUpdate->setPublishedAt($publishedAt);
        }
        
        $bookToUpdate->setEditor($isEditorExist);

        $bookUpdated = $this->booksRepository->update($bookToUpdate, $linkedAuthors, $unlinkedAuthors);
        return $this->serialize($bookUpdated, EntityTypeUtils::BOOK);
    }
}