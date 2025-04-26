<?php 

namespace App\Action\Editors;

use App\Action\AbstractActions;
use App\Entity\Editors;
use App\Entity\Users;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ListEditor extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    ){
        parent::__construct($serializer);
    }

    public function list(Users $user): string
    {   
        $editors = $this->em->getRepository(Editors::class)->findEditorsByUser($user);
        return $this->serialize($editors, EntityTypeUtils::EDITOR);
    }
}