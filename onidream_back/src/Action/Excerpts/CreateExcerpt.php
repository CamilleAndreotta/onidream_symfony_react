<?php

namespace App\Action\Excerpts;

use App\Action\AbstractActions;
use App\Dto\Excerpts\CreateExcerptDTO;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Repository\BooksRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ExcerptsRepository;
use App\Utils\EntityTypeUtils;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateExcerpt extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ExcerptsRepository $excerptsRepository,
        private BooksRepository $booksRepository,
        private CategoriesRepository $categoriesRepository
    ){
        parent::__construct($serializer);
    }

    public function create(string $json, Users $user): JsonResponse|string
    {  
        try{

            $dataFromJson = json_decode($json, true);

            if(isset($dataFromJson['excerpt'])){
                $jsonExcerpt = json_encode($dataFromJson['excerpt']);
            };

            $createExcerptDTO = $this->deserialize($jsonExcerpt, CreateExcerptDTO::class, 'json');
            $errors = $this->validator->validate($createExcerptDTO);

            if(count($errors)>0){
                return new JsonResponse($errors, 500, [], true);
            }

            if(isset($dataFromJson['book'])){
                $isBookExist = $this->booksRepository->find($dataFromJson['book']['id']);
                if($isBookExist){
                    $linkedBook = $isBookExist;
                }
            };

            if(isset($dataFromJson['categories'])){
                $isLinkedCategoriesExist = $dataFromJson['categories'];
                $linkedCategories = [];
                foreach($isLinkedCategoriesExist as $categoryInJson){
                    $linkedCategory = $this->categoriesRepository->find($categoryInJson['id']);
                    if(!$linkedCategory){
                        return new JsonResponse("La catégorie n'existe pas en base de données.", 500, [], true);
                    }
                    if(!in_array($linkedCategory, $linkedCategories)){
                        $linkedCategories[] = $linkedCategory;
                    } 
                }

            }

        } catch(Exception $e) {
            return new JsonResponse("Le format du Json n'est pas valide.", 500, [], true);
        } 

        $bookStartedOn = new DateTime($createExcerptDTO->bookStartedOn);
        $bookEndedOn = new DateTime($createExcerptDTO->bookEndedOn);

        $excerpt = new Excerpts();
        $excerpt->setText($createExcerptDTO->text);
        $excerpt->setBookStartedOn($bookStartedOn);
        $excerpt->setBookEndedOn($bookEndedOn);
        $excerpt->setBookPage($createExcerptDTO->bookPage);
        $excerpt->setCreatorId($user->getId());

        $excerpt->setBooks($linkedBook);
        $excerptSaved = $this->excerptsRepository->add($excerpt, $linkedCategories, $user);
        return $this->serialize($excerptSaved, EntityTypeUtils::EXCERPT);
    }


}