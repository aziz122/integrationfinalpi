<?php

namespace App\Repository;

use App\Entity\ReponseLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseLike[]    findAll()
 * @method ReponseLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseLike::class);
    }

    // /**
    //  * @return ReponseLike[] Returns an array of ReponseLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReponseLike
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function countByReponseAndUser($reponse, $user){
        $qb=$this->createQueryBuilder('b')
            ->select('COUNT(b)')
            ->where('b.reponse = :reponse')
            ->andWhere('b.user = :user')
            ->setParameter('reponse',$reponse)
            ->setParameter('user',$user)
            ;
        return $qb->getQuery()->getSingleResult();
    }
}
