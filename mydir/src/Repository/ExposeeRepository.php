<?php

namespace App\Repository;

use App\Entity\Exposee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exposee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exposee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exposee[]    findAll()
 * @method Exposee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExposeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exposee::class);
    }


    public function findEntitiesByString($string)
    {
        return $this->createQueryBuilder('exposee')
            ->andWhere('exposee.nom LIKE :name')
            ->setParameter('name', '%'.$string.'%')
            ->getQuery()
            ->execute();
    }
    public function findEntitiesorder()
    {
        return $this->createQueryBuilder('exposee')
            ->orderBy('exposee.dateC')
            ->getQuery()
            ->execute();
    }
    public function findEntitiesorder1()
    {
        return $this->createQueryBuilder('exposee')
            ->orderBy('exposee.nom')
            ->getQuery()
            ->execute();
    }


    // /**
    //  * @return Exposee[] Returns an array of Exposee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Exposee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
