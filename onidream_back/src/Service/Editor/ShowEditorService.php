<?php

namespace App\Service\Editor;

use App\Entity\Editors;
use App\Service\AbstractJsonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ShowEditorService extends AbstractJsonService
{   
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    )
    {
        parent::__construct($serializer, $em);
    }

    public function show(Editors $object): string
    {   
        $editor = $this->em->getRepository(Editors::class)->find($object);

        return $this->serializeObjectToJson($editor);
    }
}