<?php

namespace App\Repository;

use App\Entity\Checked;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Checked|null find($id, $lockMode = null, $lockVersion = null)
 * @method Checked|null findOneBy(array $criteria, array $orderBy = null)
 * @method Checked[]    findAll()
 * @method Checked[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Checked::class);
    }

    // /**
    //  * @return Checked[] Returns an array of Checked objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Checked
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
