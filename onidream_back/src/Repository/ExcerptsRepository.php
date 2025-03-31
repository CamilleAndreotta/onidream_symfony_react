<?php

namespace App\Repository;

use App\Entity\Excerpts;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Excerpts>
 */
class ExcerptsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private CategoriesRepository $categoriesRepository
        )
    {
        parent::__construct($registry, Excerpts::class);
    }

    public function add(Excerpts $entity, array $linkedCategories, Users $user): Excerpts
    {   
        foreach($linkedCategories as $categoryInJson){
            $entity->addCategory($categoryInJson);
        }

        $this->getEntityManager()->persist($entity);
        $entity->addUser($user);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function update(Excerpts $entity, array $linkedCategories, array $unlinkedCategories): Excerpts|null
    {

        foreach($linkedCategories as $categoryInJson){
            $entity->addCategory($categoryInJson);
        }

        foreach($unlinkedCategories as $categoryNotInJson){
            $entity->removeCategory($categoryNotInJson);
        }


        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function remove(Excerpts $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function findExcerptWithoutRelations(string $id): Excerpts|null
    {
        return $this->createQueryBuilder('e')
            ->select('e.text, e.bookStartedOn, e.bookEndedOn,  e.bookPage')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findExcerptsByUser(Users $user){
        return $this->createQueryBuilder('e')
            ->leftJoin('e.users', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function isExcerptIsLinkedWithUser(Excerpts $excerpts, Users $user){
        return $this->createQueryBuilder('e')
            ->where('e.id = :excerpt')
            ->leftJoin('e.users', 'u')
            ->andwhere('u.id = :user')
            ->setParameter('excerpt', $excerpts)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
