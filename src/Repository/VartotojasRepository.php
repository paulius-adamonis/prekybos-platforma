<?php

namespace App\Repository;

use App\Entity\Vartotojas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vartotojas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vartotojas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vartotojas[]    findAll()
 * @method Vartotojas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VartotojasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vartotojas::class);
    }

    // /**
    //  * @return Vartotojas[] Returns an array of Vartotojas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vartotojas
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
