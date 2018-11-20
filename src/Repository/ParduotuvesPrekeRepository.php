<?php

namespace App\Repository;

use App\Entity\ParduotuvesPreke;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParduotuvesPreke|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParduotuvesPreke|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParduotuvesPreke[]    findAll()
 * @method ParduotuvesPreke[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParduotuvesPrekeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParduotuvesPreke::class);
    }

    // /**
    //  * @return ParduotuvesPreke[] Returns an array of ParduotuvesPreke objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParduotuvesPreke
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
