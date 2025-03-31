<?php

namespace App\Repository;

use App\Entity\Authors;
use App\Entity\Books;
use App\Entity\Editors;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Books>
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(
        private AuthorsRepository $authorsRepository,
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Books::class);
    }

    public function add(Books $entity, array $linkedAuthors, Users $user): Books
    {   
        foreach($linkedAuthors as $authorInJson){
            $entity->addAuthor($authorInJson);
        }
        $entity->setCreatorId($user->getId());
        $entity->addUser($user);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    public function findByISBN(string $isbn): Books|null
    {   
        return $this->createQueryBuilder('e')
            ->andWhere('e.isbn =:isbn')
            ->setParameter('isbn', $isbn)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(Books $entity, array $linkedAuthors, array $unlinkedAuthors): Books
    {

        foreach($linkedAuthors as $authorInJson){
            $entity->addAuthor($authorInJson);
        }

        foreach($unlinkedAuthors as $authorNotInJson){
            $entity->removeAuthor($authorNotInJson);
        }

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function remove(Books $entity): void
    {   
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function findBookWithoutRelations(string $id): Books|null
    {
        return $this->createQueryBuilder('b')
            ->select('b.name, b.summary, b.isbn,  b.publishedAt')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findBooksByUser(Users $user){
        return $this->createQueryBuilder('b')
            ->leftJoin('b.users', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function isBookIsLinkedWithUser(Books $book, Users $user){
        return $this->createQueryBuilder('b')
            ->where('b.id = :book')
            ->leftJoin('b.users', 'u')
            ->andwhere('u.id = :user')
            ->setParameter('book', $book)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
