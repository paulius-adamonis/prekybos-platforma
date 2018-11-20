<?php

namespace App\Repository;

use App\Entity\PardPardavimas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PardPardavimas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PardPardavimas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PardPardavimas[]    findAll()
 * @method PardPardavimas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PardPardavimasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PardPardavimas::class);
    }

    // /**
    //  * @return PardPardavimas[] Returns an array of PardPardavimas objects
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
    public function findOneBySomeField($value): ?PardPardavimas
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
