<?php 

namespace App\Service\Editor;

use App\Entity\Editors;
use App\Service\AbstractJsonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ListEditorService extends AbstractJsonService
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    ){
        parent::__construct($serializer);
    }

    public function list(): string
    {
        $editors = $this->em->getRepository(Editors::class)->findAll();
        return $this->serializeArrayToJson($editors);
    }
}