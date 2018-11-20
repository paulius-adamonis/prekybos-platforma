<?php

namespace App\Repository;

use App\Entity\Komentaras;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Komentaras|null find($id, $lockMode = null, $lockVersion = null)
 * @method Komentaras|null findOneBy(array $criteria, array $orderBy = null)
 * @method Komentaras[]    findAll()
 * @method Komentaras[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KomentarasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Komentaras::class);
    }

    // /**
    //  * @return Komentaras[] Returns an array of Komentaras objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Komentaras
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
