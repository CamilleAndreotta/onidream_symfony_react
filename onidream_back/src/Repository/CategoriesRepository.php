<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categories>
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categories::class);
    }

    public function add(Categories $entity, Users $user): Categories
    {
        $this->getEntityManager()->persist($entity);
        $entity->setUsers($user);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function findByName(string $name): Categories|null
    {   
        return $this->createQueryBuilder('e')
            ->andWhere('e.name =:name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(Categories $entity): Categories
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function remove(Categories $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function findCategoryWithoutRelations(string $id): Categories|null
    {
        return $this->createQueryBuilder('c')
            ->select('c.name')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }
    
    public function findCategoriesByUser(Users $user){
        return $this->createQueryBuilder('c')
            ->select('c.name', 'c.id')
            ->where('c.users = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        ;
    }
}
