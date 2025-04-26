<?php 

namespace App\Action\Books;

use App\Action\AbstractActions;
use App\Entity\Books;
use App\Utils\EntityTypeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ListBook extends AbstractActions
{
    public function __construct(
        private EntityManagerInterface $em,
        SerializerInterface $serializer
    ){
        parent::__construct($serializer);
    }

    public function list(): string
    {
        $books = $this->em->getRepository(Books::class)->findAll();
        return $this->serialize($books, EntityTypeUtils::BOOK);
    }
}