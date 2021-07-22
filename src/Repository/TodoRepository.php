<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    /**
     * @return Todo[] Returns an array of Todo objects by date DESC
     */
    public function findAllTodosSortByNewest($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $value)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Todo[] Returns an array of Todo objects by date ASC
     */
    public function findAllTodosSortByOldest($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $value)
            ->orderBy('b.createdAt', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }

    /**
     * @method Todo[]    findAll()
     * @return Todo[] Returns an array of Todo objects by Due Date DESC
     */
    public function findAllByUserSortByDuedateNew($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $value)
            ->orderBy('b.dueDate', 'DESC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Todo[] Returns an array of Todo objects by Due Date ASC
     */
    public function findAllTodosSortByDueByOld($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $value)
            ->orderBy('b.dueDate', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Todo[] Returns an array of Todo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Todo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
