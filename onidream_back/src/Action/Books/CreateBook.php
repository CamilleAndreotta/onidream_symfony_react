<?php

namespace App\Action\Books;

use App\Action\AbstractActions;
use App\Dto\Books\CreateBookDTO;
use App\Entity\Books;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\AuthorsRepository;
use App\Repository\BooksRepository;
use App\Repository\EditorsRepository;
use App\Utils\EntityTypeUtils;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateBook extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private BooksRepository $booksRepository,
        private EditorsRepository $editorsRepository,
        private AuthorsRepository $authorsRepository,
    ){
        parent::__construct($serializer);
    }

    public function create(string $json, Users $user): JsonResponse|string
    {  
        try{

            $dataFromJson = json_decode($json, true);
            
            if(isset($dataFromJson['book'])){
                $jsonBook = json_encode($dataFromJson['book']);
            }

            $createBookDTO = $this->deserialize($jsonBook, CreateBookDTO::class, 'json');
            $errors = $this->validator->validate($createBookDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }

            $isBookExist = $this->booksRepository->findByISBN($createBookDTO->isbn);
            if($isBookExist !== null){
                throw new EntityExistException("Le livre existe déjà en base de donnée.");
            }

            if(isset($dataFromJson['editor']['id'])){
                $isEditorExist = $this->editorsRepository->find($dataFromJson['editor']['id']);
                if($isEditorExist){
                    $linkedEditor = $isEditorExist;
                };
            }

            if(isset($dataFromJson['authors'])){
                $islinkedAuthorsExist = $dataFromJson['authors'];
                $linkedAuthors = [];
                foreach($islinkedAuthorsExist as $authorInJson){
                    $linkedAuthor = $this->authorsRepository->find($authorInJson['id']);
                    if(!$linkedAuthor){
                        return new JsonResponse("L'Auteur n'existe pas en base de données.", 500, [], true);
                    }
                    if(!in_array($linkedAuthor, $linkedAuthors)){
                        $linkedAuthors[] = $linkedAuthor;
                    } 
                }
            }

        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), '500', [], true);
        } catch(Exception $e) {
            return new JsonResponse("Le format du Json n'est pas valide.", 500, [], true);
        } 

        $publishedAt = new DateTime($createBookDTO->publishedAt);

        $book = new Books();
        $book->setName($createBookDTO->name);
        $book->setSummary($createBookDTO->summary);
        $book->setIsbn($createBookDTO->isbn);
        $book->setPublishedAt($publishedAt);

        $book->setEditor($linkedEditor);
        $bookSaved = $this->booksRepository->add($book, $linkedAuthors, $user);

        return $this->serialize($bookSaved, EntityTypeUtils::BOOK);
    }


}