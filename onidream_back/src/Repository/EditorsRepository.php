<?php

namespace App\Repository;

use App\Entity\Editors;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Editors>
 */
class EditorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Editors::class);
    }

    public function add(Editors $entity, Users $user): Editors
    {
        $this->getEntityManager()->persist($entity);
        $entity->setUsers($user);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function findByName(string $name): Editors|null
    {   
        return $this->createQueryBuilder('e')
            ->andWhere('e.name =:name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(Editors $entity): Editors
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function remove(Editors $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function findEditorWithoutRelations(string $id): Editors|null
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.name')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

    public function findEditorsByUser(Users $user){
        return $this->createQueryBuilder('e')
            ->select('e.id', 'e.name')
            ->where('e.users = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        ;
    }
}
