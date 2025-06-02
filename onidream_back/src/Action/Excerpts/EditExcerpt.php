<?php

namespace App\Action\Excerpts;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Action\AbstractActions;
use App\Dto\Excerpts\UpdateExcerptDTO;
use App\Entity\Excerpts;
use App\Entity\Users;
use App\Exception\EntityExistException;
use App\Repository\BooksRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ExcerptsRepository;
use App\Service\Data\ChangeDateFormatService;
use App\Service\Data\OrderArrayDataService;
use App\Service\Security\VerifyUserPermissionsService;
use App\Utils\EntityTypeUtils;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditExcerpt extends AbstractActions
{
    public function __construct(
        SerializerInterface $serializer,
        private ExcerptsRepository $excerptsRepository,
        private ValidatorInterface $validator,
        private ChangeDateFormatService $changeDateFormat,
        private BooksRepository $booksRepository,
        private CategoriesRepository $categoriesRepository,
        private OrderArrayDataService $orderArrayData,
        private VerifyUserPermissionsService $verifyUserPermissions
    ){
        parent::__construct($serializer);
    }

    public function update(string $json, null|Excerpts $excerpt, Users $user)
    {  
        try{

            if($excerpt === null){
                throw new EntityExistException("L'extrait n'existe pas en base de données.");
            }  

            $this->verifyUserPermissions->isCreatorOfObject($excerpt, $user);
            
            $dataFromJson = json_decode($json, true);

            if(isset($dataFromJson['excerpt'])){
                $jsonExcerpt = json_encode($dataFromJson['excerpt']);
            }

            $updateExcerptDTO = $this->deserialize($jsonExcerpt, UpdateExcerptDTO::class, 'json');
            $errors = $this->validator->validate($updateExcerptDTO);
            if(count($errors)>0){
                return new JsonResponse($errors, '500', [], true);
            }

            if(isset($dataFromJson['book']['id'])){
                $isBookExist = $this->booksRepository->find($dataFromJson['book']['id']);
                if(!$isBookExist){
                    return new JsonResponse("Le livre n'existe pas en base de données.", 500, [], true);
                }
            }

            if(isset($dataFromJson['categories'])){
                $islinkedCategoriesExist = $dataFromJson['categories'];
            }

            $linkedCategories = [];
            $unlinkedCategories = [];
            foreach($islinkedCategoriesExist as $categoryInJson){
                $linkedCategory = $this->categoriesRepository->find($categoryInJson['id']);
                if(!$linkedCategory){
                    return new JsonResponse("La catégorie n'existe pas en base de données.", 500, [], true);
                }

                if($linkedCategory && !in_array($linkedCategory, $linkedCategories)){
                    $linkedCategories[] = $linkedCategory;
                } 
            }
            foreach($excerpt->getCategories() as $currentLinkedCategory){
                if(!in_array($currentLinkedCategory, $linkedCategories)){
                    $unlinkedCategories[] = $currentLinkedCategory;
                }
            }

        } catch(EntityExistException $e){
            return new JsonResponse($e->getMessage(), 500, [], true);
        } catch(AccessDeniedException $e){
            return new JsonResponse($e->getMessage(), 403, [], true);
        }

        $excerptToUpdate = $this->deserialize($jsonExcerpt, Excerpts::class, $excerpt);

        if($updateExcerptDTO->text) {
            $text = html_entity_decode($updateExcerptDTO->text, ENT_QUOTES, 'UTF-8');
            $excerptToUpdate->setText($text);
        }

        if($updateExcerptDTO->bookStartedOn){
            $bookStartedOn = new DateTime($updateExcerptDTO->bookStartedOn);
            $excerptToUpdate->setBookStartedOn($bookStartedOn);
        }

        if($updateExcerptDTO->bookEndedOn){
            $bookEndedOn = new DateTime($updateExcerptDTO->bookEndedOn);
            $excerptToUpdate->setBookEndedOn($bookEndedOn);
        }

        $excerptToUpdate->setBooks($isBookExist);
        $excerptUpdated = $this->excerptsRepository->update($excerptToUpdate, $linkedCategories, $unlinkedCategories);
        return $this->serialize($excerptUpdated, EntityTypeUtils::EXCERPT);
    }
}