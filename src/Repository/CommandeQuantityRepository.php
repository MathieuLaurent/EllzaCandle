<?php

namespace App\Repository;

use App\Entity\CommandeQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandeQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeQuantity[]    findAll()
 * @method CommandeQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeQuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeQuantity::class);
    }

    // /**
    //  * @return CommandeQuantity[] Returns an array of CommandeQuantity objects
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
    public function findOneBySomeField($value): ?CommandeQuantity
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
