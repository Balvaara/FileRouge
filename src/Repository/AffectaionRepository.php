<?php

namespace App\Repository;

use App\Entity\Affectaion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Affectaion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affectaion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affectaion[]    findAll()
 * @method Affectaion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectaionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectaion::class);
    }

    // /**
    //  * @return Affectaion[] Returns an array of Affectaion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Affectaion
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
