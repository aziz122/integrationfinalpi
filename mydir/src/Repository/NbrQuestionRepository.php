<?php

namespace App\Repository;

use App\Entity\NbrQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NbrQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method NbrQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method NbrQuestion[]    findAll()
 * @method NbrQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NbrQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbrQuestion::class);
    }

    // /**
    //  * @return NbrQuestion[] Returns an array of NbrQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NbrQuestion
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
