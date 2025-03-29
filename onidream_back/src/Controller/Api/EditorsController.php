<?php

namespace App\Controller\Api;

use App\Entity\Editors;
use App\Service\Editor\CreateEditorService;
use App\Service\Editor\DeleteEditorService;
use App\Service\Editor\EditEditorService;
use App\Service\Editor\ListEditorService;
use App\Service\Editor\ShowEditorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class EditorsController extends AbstractController
{   
    public function __construct(
        private ListEditorService $listEditors,
        private ShowEditorService $showEditor,
        private CreateEditorService $createEditor,
        private EditEditorService $editEditor,
        private DeleteEditorService $deleteEditor
    ){}
    
    #[Route('/api/editors', name: 'app_api_editors', methods:['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse($this->listEditors->list(), '200', [], true);
    }

    #[Route('/api/editors/{editor}', name: 'app_api_editor', methods:['GET'])]
    public function findOne(Editors $editor ): JsonResponse
    {   
        return new JsonResponse($this->showEditor->show($editor), '200', [], true);
    }

    #[Route('/api/editors', name: 'app_api_editor_create', methods:['POST'])]
    public function create(Request $request): JsonResponse
    {   
        $json = $request->getContent();
        return new JsonResponse($this->createEditor->create($json), '200', [], true);
    }

    #[Route('/api/editors/{editor}', name: 'app_api_editor_update', methods:['PUT', 'PATCH'])]
    public function update(null|Editors $editor, Request $request): JsonResponse
    {   
        $json = $request->getContent();
        return new JsonResponse($this->editEditor->update($json, $editor), '200', [], true);
    }

    #[Route('/api/editors/{editor}', name: 'app_api_editor_delete', methods:['DELETE'])]
    public function delete(null|Editors $editor): JsonResponse
    {   
        return new JsonResponse($this->deleteEditor->delete($editor), '200', [], true);
    }

}
