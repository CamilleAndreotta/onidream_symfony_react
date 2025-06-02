<?php

namespace App\Repository;

use App\Entity\Authors;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autors>
 */
class AuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authors::class);
    }

    public function add(Authors $entity, Users $user): Authors
    {
        $this->getEntityManager()->persist($entity);
        $entity->setUsers($user);
        $this->getEntityManager()->flush();

        return $entity;
    }

    public function findByName(string $firstName, string $lastName): Authors|null
    {   
        return $this->createQueryBuilder('e')
            ->andWhere('e.firstname =:firstname')
            ->andWhere('e.lastname =:lastname')
            ->setParameter('firstname', $firstName)
            ->setParameter('lastname', $lastName)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(Authors $entity): Authors
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function remove(Authors $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function findAuthorWithoutRelations(string $id): Authors|null
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.firstname', 'a.lastname', 'a.birthDate', 'a.deathDate', 'a.biography')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

    public function findAuthorsByUser(Users $user){
        return $this->createQueryBuilder('a')
            ->select('a.id, a.firstname', 'a.lastname', 'a.birthDate', 'a.deathDate', 'a.biography')
            ->where('a.users = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        ;
    }
}
