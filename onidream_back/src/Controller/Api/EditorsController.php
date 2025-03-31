<?php

namespace App\Controller\Api;

use App\Action\Editors\CreateEditor;
use App\Action\Editors\DeleteEditor;
use App\Action\Editors\EditEditor;
use App\Action\Editors\ListEditor;
use App\Action\Editors\ShowEditor;
use App\Entity\Editors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class EditorsController extends AbstractController
{   
    public function __construct(
        private ListEditor $listEditors,
        private ShowEditor $showEditor,
        private CreateEditor $createEditor,
        private EditEditor $editEditor,
        private DeleteEditor $deleteEditor
    ){}
    
    #[Route('/api/editors', name: 'app_api_editors', methods:['GET'])]
    public function index(Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->listEditors->list($user), '200', [], true);
    }

    #[Route('/api/editors/{editor}', name: 'app_api_editor', methods:['GET'])]
    public function findOne(null|Editors $editor, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->showEditor->show($editor, $user), '200', [], true);
    }

    #[Route('/api/editors', name: 'app_api_editor_create', methods:['POST'])]
    public function create(Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->createEditor->create($json, $user), '200', [], true);
    }

    #[Route('/api/editors/{editor}', name: 'app_api_editor_update', methods:['PUT', 'PATCH'])]
    public function update(null|Editors $editor, Request $request, Security $security): JsonResponse
    {   
        $json = $request->getContent();
        $user = $security->getUser();
        return new JsonResponse($this->editEditor->update($json, $editor, $user), '200', [], true);
    }

    #[Route('/api/editors/{editor}', name: 'app_api_editor_delete', methods:['DELETE'])]
    public function delete(null|Editors $editor, Security $security): JsonResponse
    {   
        $user = $security->getUser();
        return new JsonResponse($this->deleteEditor->delete($editor, $user), '200', [], true);
    }

}
