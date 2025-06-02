<?php

namespace App\Controller\Api;

use App\Action\Excerpts\CreateExcerpt;
use App\Action\Excerpts\DeleteExcerpt;
use App\Action\Excerpts\EditExcerpt;
use App\Action\Excerpts\ListExcerpt;
use App\Action\Excerpts\ListExcerptByUser;
use App\Action\Excerpts\ShowExcerpt;
use App\Entity\Excerpts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ExcerptsController extends AbstractController
{   
    public function __construct(
        private ListExcerpt $listExcerpts,
        private ShowExcerpt $showExcerpt,
        private CreateExcerpt $createExcerpt,
        private EditExcerpt $editExcerpt,
        private DeleteExcerpt $deleteExcerpt,
        private ListExcerptByUser $listExcerptByUser
    ){}
    
    #[Route('/api/excerpts', name: 'app_api_excerpts', methods:['GET'])]
    public function index(Security $security): JsonResponse
    {   
        $user = $security->getUser();        
        return new JsonResponse($this->listExcerpts->list($user), 200, [], true);
    }

    #[Route('/api/excerpts/user', name: 'app_api_excerpts_user', methods:['GET'])]
    public function indexExcerptsByUser(Security $security, Request $request): JsonResponse
    {   
        $searchTerm = $request->get('search');
        $user = $security->getUser();
        return new JsonResponse($this->listExcerptByUser->list($user, $searchTerm), 200, [], true);
    }

    #[Route('/api/excerpts/{excerpt}', name: 'app_api_excerpt', methods:['GET'])]
    public function findOne(null|Excerpts $excerpt, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->showExcerpt->show($excerpt, $user), 200, [], true);
    }

    #[Route('/api/excerpts', name: 'app_api_excerpt_create', methods:['POST'])]
    public function create(Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->createExcerpt->create($json, $user), 200, [], true);
    }

    #[Route('/api/excerpts/{excerpt}', name: 'app_api_excerpt_update', methods:['PUT', 'PATCH'])]
    public function update(null|Excerpts $excerpt, Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->editExcerpt->update($json, $excerpt, $user), 200, [], true);
    }

    #[Route('/api/excerpts/{excerpt}', name: 'app_api_excerpt_delete', methods:['DELETE'])]
    public function delete(null|Excerpts $excerpt, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->deleteExcerpt->delete($excerpt, $user), 200, [], true);
    }

}
